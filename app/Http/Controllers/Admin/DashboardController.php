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
        // 1. Hitung jumlah seluruh pengiriman aktif yang sedang berjalan di lapangan (selain selesai atau ditunda)
        $activeShipments = Shipment::whereNotIn('current_status', ['Diterima', 'Selesai','Penundaan Pengiriman'])->count();

        // 2. Hitung total kurir yang terdaftar secara resmi di dalam sistem
        $totalCouriers = User::where('role', 'kurir')->count();

        // 3. Hitung jumlah seluruh paket yang telah berhasil diserahkan ke tangan pelanggan
        $deliveredShipments = Shipment::whereIn('current_status', ['Diterima', 'Selesai'])->count();

        // 4. Hitung jumlah paket yang mengalami kendala atau penundaan saat pengantaran
        $delayedShipments = Shipment::where('current_status', 'Penundaan Pengiriman')->count();

        // 5. Siapkan data statistik jumlah pembuatan resi harian untuk grafik (7 hari terakhir)
        $chartData = [];
        $chartLabels = [];
        $maxChartValue = 1;

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Shipment::whereDate('created_at', $date->format('Y-m-d'))->count();

            $chartData[] = $count;
            $chartLabels[] = $date->isoFormat('ddd'); // Sen, Sel, Rab, dll

            if ($count > $maxChartValue) {
                $maxChartValue = $count;
            }
        }

        // 6. Evaluasi performa kurir (Top 3) berdasarkan jumlah total paket yang berhasil mereka selesaikan
        $topCouriers = User::where('role', 'kurir')
            ->withCount(['manifests as total_delivered' => function ($query) {
                $query->join('shipments', 'manifests.id', '=', 'shipments.manifest_id')
                      ->whereIn('shipments.current_status', ['Diterima', 'Selesai']);
            }])
            ->orderByDesc('total_delivered')
            ->take(3)
            ->get();

        // 7. Ambil data 5 pembaruan resi terakhir untuk ditampilkan di linimasa dashboard
        $recentShipments = Shipment::with('manifest.courier')->latest()->take(5)->get();

        // 8. Kirim seluruh rangkuman data analitik ke tampilan dashboard admin
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
