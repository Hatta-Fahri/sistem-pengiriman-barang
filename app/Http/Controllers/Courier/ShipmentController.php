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

            // Proses upload Base64 langsung ke Cloudinary
            if ($request->filled('photo_base64')) {
                $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
                $uploadResult = $cloudinary->uploadApi()->upload($request->photo_base64, [
                    'folder' => 'pod',
                ]);
                $dataPod['photo_path'] = $uploadResult['secure_url'];
            }

            ProofOfDelivery::updateOrCreate(
                ['shipment_id' => $shipment->id],
                $dataPod
            );
        }

        return back()->with('success', 'Status resi ' . $shipment->tracking_number . ' berhasil diupdate!');
    }
}

