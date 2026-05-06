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
        // 1. Dapatkan ID kurir yang sedang menggunakan sistem
        $courierId = Auth::id();

        // 2. Ambil daftar resi yang pernah diantarkan oleh kurir ini melalui riwayat manifest, beserta data bukti pengirimannya (POD)
        $query = Shipment::with('proofOfDelivery')
            ->whereHas('manifest', function ($q) use ($courierId) {
                $q->where('courier_id', $courierId);
            })
            // 3. Saring riwayat agar hanya menampilkan paket yang status akhirnya sudah jelas (berhasil, gagal, atau ditunda)
            ->whereIn('current_status', ['Diterima', 'Selesai', 'Gagal Dikirim', 'Penundaan Pengiriman']);

        // 4. Terapkan filter pencarian jika kurir memasukkan nomor resi atau nama penerima di kolom pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%");
            });
        }

        // 5. Urutkan hasil riwayat dari yang paling baru diselesaikan dan bagi ke dalam beberapa halaman (pagination)
        $shipments = $query->latest('updated_at')->paginate(12);

        // 6. Tampilkan daftar riwayat pengiriman ke layar kurir
        return view('courier.history.index', compact('shipments'));
    }
}
