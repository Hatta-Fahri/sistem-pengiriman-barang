<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $courierId = Auth::id();

        // Cari manifest yang dibawa oleh kurir ini
        $query = Shipment::with('proofOfDelivery')
            ->whereHas('manifest', function ($q) use ($courierId) {
                $q->where('courier_id', $courierId);
            })
            // Hanya ambil status yang sudah selesai/final
            ->whereIn('current_status', ['Diterima', 'Selesai', 'Gagal Dikirim', 'Penundaan Pengiriman']);

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%");
            });
        }

        // Urutkan dari yang terbaru diselesaikan
        $shipments = $query->latest('updated_at')->paginate(12);

        return view('courier.history.index', compact('shipments'));
    }
}
