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
        $courier = Auth::user();

        $activeManifest = Manifest::with('shipments')
            ->where('courier_id', $courier->id)
            ->where('status', 'Sedang Jalan')
            ->first();

        return view('courier.shipments.index', compact('activeManifest'));
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $request->validate([
            'current_status' => 'required|string',
        ]);

        // 1. Update status utama
        $shipment->update([
            'current_status' => $request->current_status
        ]);

        // 2. Jika statusnya DITERIMA, olah nama dan foto Base64-nya
        if ($request->current_status === 'Diterima') {
            $request->validate([
                'received_by_name' => 'required|string|max:255',
                'photo_base64' => 'required|string', // Validasi input kamera langsung
            ]);

            $dataPod = [
                'received_by_name' => $request->received_by_name,
                'delivered_at' => now(),
            ];

            // Proses mengubah gambar Base64 menjadi file JPG fisik
            if ($request->filled('photo_base64')) {
                $image_parts = explode(";base64,", $request->photo_base64);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1] ?? 'jpg';
                $image_base64 = base64_decode($image_parts[1]);

                // Beri nama file unik: pod_KEN-XXXX_123456.jpg
                $fileName = 'pod_' . $shipment->tracking_number . '_' . time() . '.' . $image_type;
                $path = 'pod/' . $fileName;

                // Simpan ke storage/app/public/pod
                Storage::disk('public')->put($path, $image_base64);
                $dataPod['photo_path'] = $path;
            }

            ProofOfDelivery::updateOrCreate(
                ['shipment_id' => $shipment->id],
                $dataPod
            );
        }

        return back()->with('success', 'Status resi ' . $shipment->tracking_number . ' berhasil diupdate!');
    }
}

