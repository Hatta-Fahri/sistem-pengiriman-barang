<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $resi = $request->query('resi');
        $shipment = null;
        $error = null;

        if ($resi) {
            $shipment = Shipment::where('tracking_number', $resi)->first();

            if (!$shipment) {
                $error = "Nomor resi '$resi' tidak ditemukan. Pastikan nomor yang dimasukkan sudah benar.";
            }
        }

        // Kembalikan ke halaman welcome (landing page)
        return view('welcome', compact('shipment', 'resi', 'error'));
    }
}
