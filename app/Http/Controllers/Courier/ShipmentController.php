<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Manifest;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function index()
    {
        $courier = Auth::user();

        $activeManifest = Manifest::with('shipments')
            ->where('courier_id', $courier->id)
            ->where('status', 'Sedang Jalan')
            ->first();

        return view('courier.shipments.index', compact('activeManifest'));
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $request->validate([
            'current_status' => 'required|string',
        ]);

        $shipment->update([
            'current_status' => $request->current_status
        ]);

        return back()->with('success', 'Status resi ' . $shipment->tracking_number . ' berhasil diubah!');
    }
}
