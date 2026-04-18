<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Manifest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. PENGIRIMAN AKTIF (Semua status kecuali Selesai, Gagal, Ditunda)
        $activeShipments = Shipment::whereNotIn('current_status', ['Diterima', 'Selesai', 'Gagal Dikirim', 'Penundaan Pengiriman'])->count();

        // 2. TOTAL KURIR (Menggunakan enum 'kurir' dari tabel users)
        $totalCouriers = User::where('role', 'kurir')->count();

        // 3. PAKET TERKIRIM (Berhasil sampai ke tangan customer)
        $deliveredShipments = Shipment::whereIn('current_status', ['Diterima', 'Selesai'])->count();

        // 4. KENDALA PENGIRIMAN (Status Penundaan Pengiriman)
        $delayedShipments = Shipment::where('current_status', 'Penundaan Pengiriman')->count();

        // 5. GRAFIK AKTIVITAS (7 Hari Terakhir)
        $chartData = [];
        $chartLabels = [];
        $maxChartValue = 1; // Mencegah pembagian dengan nol di view

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Shipment::whereDate('created_at', $date->format('Y-m-d'))->count();

            $chartData[] = $count;
            $chartLabels[] = $date->isoFormat('ddd'); // Sen, Sel, Rab, dll

            if ($count > $maxChartValue) {
                $maxChartValue = $count;
            }
        }

        // 6. PERFORMA KURIR (Top 3 berdasarkan jumlah paket yang berhasil diantar)
        // Menggunakan Eloquent withCount pada relasi melalui manifests
        $topCouriers = User::where('role', 'kurir')
            ->withCount(['manifests as total_delivered' => function ($query) {
                // Bergabung dengan tabel shipments untuk menghitung paket sukses di dalam manifest si kurir
                $query->join('shipments', 'manifests.id', '=', 'shipments.manifest_id')
                      ->whereIn('shipments.current_status', ['Diterima', 'Selesai']);
            }])
            ->orderByDesc('total_delivered')
            ->take(3)
            ->get();

        // 7. UPDATE TERKINI (5 Resi Terbaru)
        $recentShipments = Shipment::with('manifest.courier')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'activeShipments',
            'totalCouriers',
            'deliveredShipments',
            'delayedShipments',
            'chartData',
            'chartLabels',
            'maxChartValue',
            'topCouriers',
            'recentShipments'
        ));
    }
}
