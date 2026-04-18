<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Manifest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan Halaman Pusat Laporan (Index)
     */
    public function index()
    {
        // Statistik ringkas murni operasional (TANPA UANG)
        $stats = [
            'total_shipments' => Shipment::count(),
            'total_tonase'    => Shipment::sum('weight'), // Mengganti revenue dengan total tonase
            'total_couriers'  => User::where('role', 'kurir')->count(),
            'total_manifests' => Manifest::where('status', 'Selesai')->count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    /**
     * 📦 LAPORAN 1: Shipment Report
     */
    public function shipmentReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());

        $query = Shipment::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        $shipments = $query->latest()->get();

        // Ringkasan operasional (TANPA OMZET)
        $stats = [
            'total_resi'   => $query->count(),
            'total_berat'  => $query->sum('weight'),
            'total_koli'   => $query->sum('jumlah_koli'), // Mengganti omzet dengan jumlah koli
            'status_count' => $query->get()->groupBy('current_status')->map->count()
        ];

        return view('admin.reports.shipments', compact('shipments', 'stats', 'startDate', 'endDate'));
    }

    /**
     * 🛵 LAPORAN 2: Courier Performance
     */
    public function courierPerformance(Request $request)
    {
        $couriers = User::where('role', 'kurir')
            ->withCount(['manifests' => function($q) {
                $q->where('status', 'Selesai');
            }])
            ->get();

        return view('admin.reports.couriers', compact('couriers'));
    }
}
