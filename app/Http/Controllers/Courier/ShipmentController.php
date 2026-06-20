<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Manifest;
use App\Models\Shipment;
use App\Models\ProofOfDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShipmentController extends Controller
{
    public function index()
    {
        // 1. Ambil data profil kurir yang sedang login saat ini
        $courier = Auth::user();

        // 2. Cari manifest aktif: berstatus Persiapan, Ditugaskan, ATAU Sedang Jalan milik kurir ini
        $activeManifest = Manifest::with('shipments')
            ->where('courier_id', $courier->id)
            ->whereIn('status', ['Persiapan', 'Ditugaskan', 'Sedang Jalan'])
            ->first();

        // 3. Tentukan apakah perjalanan sudah benar-benar dimulai (departed_at terisi)
        //    Jika belum, tombol update status tidak boleh aktif
        $hasStarted = $activeManifest
            && $activeManifest->status === 'Sedang Jalan'
            && $activeManifest->departed_at !== null;

        return view('courier.shipments.index', compact('activeManifest', 'hasStarted'));
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        // 1. Pastikan kurir sudah memulai perjalanan sebelum boleh update status
        $hasStarted = Manifest::where('courier_id', Auth::id())
            ->where('status', 'Sedang Jalan')
            ->whereNotNull('departed_at')
            ->exists();

        if (!$hasStarted) {
            return back()->withErrors('Perjalanan belum dimulai. Tekan tombol "Mulai Perjalanan Sekarang" di dashboard terlebih dahulu.');
        }

        // 2. Validasi input pembaruan status dari kurir
        $request->validate([
            'current_status' => 'required|string',
        ]);

        // 3. Jika status yang dipilih adalah "Diterima", lakukan validasi lengkap POD TERLEBIH DAHULU
        //    sebelum menyentuh database, agar status tidak terlanjur tersimpan jika foto tidak ada.
        if ($request->current_status === 'Diterima') {
            $request->validate([
                'received_by_name' => 'required|string|max:255',
                'photo_base64'     => 'required|string',
            ]);
        }

        // 4. Setelah semua validasi lolos, baru perbarui status di database
        $shipment->update([
            'current_status' => $request->current_status
        ]);

        // 5. Tangani penyimpanan data Proof of Delivery jika status "Diterima"
        if ($request->current_status === 'Diterima') {
            $dataPod = [
                'received_by_name' => $request->received_by_name,
                'delivered_at'     => now(),
            ];

            // 6. Proses foto format Base64 dan unggah langsung ke sistem penyimpanan awan (Cloudinary)
            if ($request->filled('photo_base64')) {
                $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
                $uploadResult = $cloudinary->uploadApi()->upload($request->photo_base64, [
                    'folder' => 'pod',
                ]);
                $dataPod['photo_path'] = $uploadResult['secure_url'];
            }

            // 7. Simpan seluruh data POD ke database terkait resi ini
            ProofOfDelivery::updateOrCreate(
                ['shipment_id' => $shipment->id],
                $dataPod
            );
        }

        return back()->with('success', 'Status resi ' . $shipment->tracking_number . ' berhasil diupdate!');
    }
}

