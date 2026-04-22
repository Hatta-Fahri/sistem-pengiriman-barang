<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        // Validasi format dihapus karena sekarang default langsung ke PDF (Print View)
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|in:shipment,courier'
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $type = $request->report_type;

        if ($type === 'shipment') {
            return $this->generateShipmentReport($startDate, $endDate);
        } else {
            return $this->generateCourierReport($startDate, $endDate);
        }
    }

    private function generateShipmentReport($start, $end)
    {
        $shipments = Shipment::whereBetween('created_at', [$start, $end])->latest()->get();

        // Menghitung Tonase (Total Berat) dan Total Pendapatan
        $totalWeight = $shipments->sum('weight');
        $totalCost = $shipments->sum('shipping_cost');

        // Langsung return ke view cetak PDF
        return view('admin.reports.print-shipment', compact('shipments', 'start', 'end', 'totalWeight', 'totalCost'));
    }

    private function generateCourierReport($start, $end)
    {
        // Tarik data resi yang statusnya sudah Selesai/Diterima beserta relasi lengkapnya
        $shipments = Shipment::with(['manifest.courier', 'manifest.vehicle', 'proofOfDelivery'])
            ->whereBetween('updated_at', [$start, $end])
            ->whereIn('current_status', ['Diterima', 'Selesai'])
            ->latest('updated_at')
            ->get();

        // Langsung return ke view cetak PDF
        return view('admin.reports.print-detail', compact('shipments', 'start', 'end'));
    }
}
