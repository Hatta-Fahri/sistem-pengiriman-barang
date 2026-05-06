<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Manifest;
use App\Models\Shipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Dapatkan informasi profil kurir yang sedang login
        $courier = Auth::user();

        // 2. Cari tugas jadwal (manifest) aktif yang sedang dijalankan oleh kurir ini
        $activeManifest = Manifest::with(['vehicle', 'shipments'])
            ->where('courier_id', $courier->id)
            ->where('status', 'Sedang Jalan')
            ->first();

        // 3. Hitung total keseluruhan paket yang sudah pernah berhasil diantarkan oleh kurir ini sepanjang waktu
        $totalDelivered = Shipment::whereHas('manifest', function ($query) use ($courier) {
                $query->where('courier_id', $courier->id);
            })
            ->whereIn('current_status', ['Diterima', 'Selesai'])
            ->count();

        // 4. Kirim rangkuman data ke tampilan dashboard utama kurir
        return view('courier.dashboard', compact('courier', 'activeManifest', 'totalDelivered'));
    }

    public function startJourney(Manifest $manifest)
    {
        // 1. Validasi keamanan ganda: Pastikan jadwal (manifest) yang akan dimulai ini benar-benar milik kurir yang login
        if ($manifest->courier_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($manifest) {
            // 2. Catat stempel waktu (timestamp) keberangkatan armada pada manifest
            $manifest->update(['departed_at' => now()]);

            // 3. Ambil seluruh data resi yang tergabung dalam jadwal ini
            $shipments = Shipment::where('manifest_id', $manifest->id)->get();

            foreach ($shipments as $shipment) {
                $statusVal = $shipment->current_status->value ?? $shipment->current_status;

                // 4. Terapkan logika cerdas penentuan status: Jika ini adalah paket tertunda dari jadwal sebelumnya, langsung ubah menjadi 'Dalam Pengantaran'
                if ($statusVal === 'Penundaan Pengiriman') {
                    $shipment->current_status = 'Dalam Pengantaran';
                } else {
                    // 5. Jika ini adalah paket reguler baru, ubah status awalnya menjadi 'Dalam Perjalanan'
                    $shipment->current_status = 'Dalam Perjalanan';
                }

                // 6. Simpan perubahan status paket ke dalam database agar pelacakan pelanggan diperbarui
                $shipment->save();
            }
        });

        return back()->with('success', 'Perjalanan dimulai! Status tracking pelanggan telah diperbarui.');
    }
}
