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
        // 1. Validasi filter tanggal dan jenis laporan yang diminta oleh admin
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|in:shipment,courier'
        ]);

        // 2. Format tanggal mulai dan akhir agar mencakup seluruh jam pada hari tersebut (00:00 - 23:59)
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $type = $request->report_type;

        // 3. Arahkan pembuatan laporan sesuai dengan jenis yang dipilih (laporan umum resi atau laporan kinerja kurir)
        if ($type === 'shipment') {
            return $this->generateShipmentReport($startDate, $endDate);
        } else {
            return $this->generateCourierReport($startDate, $endDate);
        }
    }

    private function generateShipmentReport($start, $end)
    {
        // 1. Ambil seluruh data resi yang dibuat dalam rentang waktu yang dipilih
        $shipments = Shipment::whereBetween('created_at', [$start, $end])->latest()->get();

        // 2. Hitung total tonase (keseluruhan berat) dan total pendapatan dari ongkos kirim
        $totalWeight = $shipments->sum('weight');
        $totalCost = $shipments->sum('shipping_cost');

        // 3. Tampilkan data laporan rekapitulasi resi ke format cetakan (Print View)
        return view('admin.reports.print-shipment', compact('shipments', 'start', 'end', 'totalWeight', 'totalCost'));
    }

    private function generateCourierReport($start, $end)
    {
        // 1. Tarik data resi yang statusnya sudah Selesai/Diterima dalam rentang waktu yang dipilih, beserta relasi lengkap kurir, armada, dan foto bukti pengiriman (POD)
        $shipments = Shipment::with(['manifest.courier', 'manifest.vehicle', 'proofOfDelivery'])
            ->whereBetween('updated_at', [$start, $end])
            ->whereIn('current_status', ['Diterima', 'Selesai'])
            ->latest('updated_at')
            ->get();

        // 2. Tampilkan laporan detail kinerja kurir ke format cetakan (Print View)
        return view('admin.reports.print-detail', compact('shipments', 'start', 'end'));
    }
}
