<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Manifest;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class ManifestController extends Controller
{
    public function complete(Manifest $manifest)
    {
        $unfinished = $manifest->shipments->filter(function ($shipment) {
            $status = $shipment->current_status->value ?? $shipment->current_status;
            return !in_array($status, ['Diterima', 'Gagal Dikirim', 'Penundaan Pengiriman']);
        })->count();

        if ($unfinished > 0) {
            return back()->withErrors("Gagal! Masih ada {$unfinished} paket yang belum diselesaikan.");
        }

        DB::transaction(function () use ($manifest) {
            $manifest->update([
                'status' => 'Selesai',
            ]);

            if ($manifest->vehicle_id) {
                Vehicle::where('id', $manifest->vehicle_id)->update(['status' => 'Tersedia']);
            }
        });

        return redirect()->route('courier.dashboard')
            ->with('success', 'Tugas Selesai! Kendaraan telah dikembalikan.');
    }
}
