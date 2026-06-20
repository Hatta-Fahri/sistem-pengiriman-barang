<?php

namespace App\Http\Controllers\Courier;

use App\Enums\VehicleStatus;
use App\Http\Controllers\Controller;
use App\Models\Manifest;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class ManifestController extends Controller
{
    public function complete(Manifest $manifest)
    {
        // 1. Cek apakah masih ada resi dalam manifest ini yang belum diselesaikan (statusnya belum Diterima atau belum ditandai sebagai Penundaan)
        $unfinished = $manifest->shipments->filter(function ($shipment) {
            $status = $shipment->current_status->value ?? $shipment->current_status;
            return !in_array($status, ['Diterima','Penundaan Pengiriman']);
        })->count();

        // 2. Tolak penyelesaian tugas manifest jika masih ada paket yang statusnya menggantung
        if ($unfinished > 0) {
            return back()->withErrors("Gagal! Masih ada {$unfinished} paket yang belum diselesaikan.");
        }

        DB::transaction(function () use ($manifest) {
            // 3. Tandai status keseluruhan manifest menjadi Selesai karena semua paket sudah beres
            $manifest->update([
                'status' => 'Selesai',
            ]);

            // 4. Kembalikan status armada kendaraan menjadi Tersedia agar bisa dipakai oleh jadwal lain
            if ($manifest->vehicle_id) {
                Vehicle::where('id', $manifest->vehicle_id)->update(['status' => VehicleStatus::TERSEDIA]);
            }
        });

        // 5. Arahkan kurir kembali ke halaman utama dengan pesan konfirmasi sukses
        return redirect()->route('courier.dashboard')
            ->with('success', 'Tugas Selesai! Kendaraan telah dikembalikan.');
    }
}
