<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Manifest;
use App\Models\Shipment; // 👇 Tambahin ini jangan lupa
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $courier = Auth::user();

        // Data manifest yang lagi aktif jalan (Kode lama tetap dipertahankan)
        $activeManifest = Manifest::with(['vehicle', 'shipments'])
            ->where('courier_id', $courier->id)
            ->where('status', 'Sedang Jalan')
            ->first();

        // 👇 LOGIKA BARU: Hitung total resi yang sukses diantar kurir ini
        $totalDelivered = Shipment::whereHas('manifest', function ($query) use ($courier) {
                $query->where('courier_id', $courier->id);
            })
            ->whereIn('current_status', ['Diterima', 'Selesai'])
            ->count();

        // Lempar variabel $totalDelivered ke view
        return view('courier.dashboard', compact('courier', 'activeManifest', 'totalDelivered'));
    }
}

