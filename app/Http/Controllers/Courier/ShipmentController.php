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

        // 2. Cari jadwal (manifest) yang saat ini sedang aktif (Sedang Jalan) dan ditugaskan kepada kurir ini
        $activeManifest = Manifest::with('shipments')
            ->where('courier_id', $courier->id)
            ->where('status', 'Sedang Jalan')
            ->first();

        return view('courier.shipments.index', compact('activeManifest'));
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        // 1. Validasi input pembaruan status yang dikirimkan oleh kurir dari lapangan
        $request->validate([
            'current_status' => 'required|string',
        ]);

        // 2. Perbarui status perjalanan utama paket di database
        $shipment->update([
            'current_status' => $request->current_status
        ]);

        // 3. Tangani alur khusus jika paket berstatus Diterima: Validasi kelengkapan nama penerima dan foto bukti (POD)
        if ($request->current_status === 'Diterima') {
            $request->validate([
                'received_by_name' => 'required|string|max:255',
                'photo_base64' => 'required|string',
            ]);

            $dataPod = [
                'received_by_name' => $request->received_by_name,
                'delivered_at' => now(),
            ];

            // 4. Proses foto format Base64 dan unggah langsung ke sistem penyimpanan awan (Cloudinary)
            if ($request->filled('photo_base64')) {
                $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
                $uploadResult = $cloudinary->uploadApi()->upload($request->photo_base64, [
                    'folder' => 'pod',
                ]);
                $dataPod['photo_path'] = $uploadResult['secure_url'];
            }

            // 5. Simpan seluruh data pelengkap Proof of Delivery ke dalam database terkait resi ini
            ProofOfDelivery::updateOrCreate(
                ['shipment_id' => $shipment->id],
                $dataPod
            );
        }

        return back()->with('success', 'Status resi ' . $shipment->tracking_number . ' berhasil diupdate!');
    }
}

