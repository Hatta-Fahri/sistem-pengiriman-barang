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
        $courier = Auth::user();

        $activeManifest = Manifest::with(['vehicle', 'shipments'])
            ->where('courier_id', $courier->id)
            ->where('status', 'Sedang Jalan')
            ->first();

        $totalDelivered = Shipment::whereHas('manifest', function ($query) use ($courier) {
                $query->where('courier_id', $courier->id);
            })
            ->whereIn('current_status', ['Diterima', 'Selesai'])
            ->count();

        return view('courier.dashboard', compact('courier', 'activeManifest', 'totalDelivered'));
    }

    public function startJourney(Manifest $manifest)
    {
        // Pastikan manifest ini benar-benar milik kurir yang login
        if ($manifest->courier_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($manifest) {
            // Set waktu keberangkatan truk
            $manifest->update(['departed_at' => now()]);

            $shipments = Shipment::where('manifest_id', $manifest->id)->get();

            // 👇 LOGIKA PINTAR: Update status sesuai riwayatnya
            foreach ($shipments as $shipment) {
                $statusVal = $shipment->current_status->value ?? $shipment->current_status;

                if ($statusVal === 'Penundaan Pengiriman') {
                    // Kalau ini paket tunda dari hari kemarin, langsung status otw rumah
                    $shipment->current_status = 'Dalam Pengantaran';
                } else {
                    // Kalau ini paket baru, statusnya dalam perjalanan antar kota dulu
                    $shipment->current_status = 'Dalam Perjalanan';
                }

                $shipment->save();
            }
        });

        return back()->with('success', 'Perjalanan dimulai! Status tracking pelanggan telah diperbarui.');
    }
}
