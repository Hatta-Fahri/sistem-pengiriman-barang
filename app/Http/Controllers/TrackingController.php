<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil nomor resi yang dicari oleh pengguna dari parameter URL
        $resi = $request->query('resi');
        $shipment = null;
        $error = null;

        if ($resi) {
            // 2. Cari data pengiriman di database berdasarkan nomor resi
            $shipment = Shipment::where('tracking_number', $resi)->first();

            // 3. Tangani kasus jika nomor resi tidak terdaftar di sistem
            if (!$shipment) {
                $error = "Nomor resi '$resi' tidak ditemukan. Pastikan nomor yang dimasukkan sudah benar.";
            }
        }

        // 4. Tampilkan halaman utama pencarian beserta hasil status pengiriman
        return view('welcome', compact('shipment', 'resi', 'error'));
    }
}
