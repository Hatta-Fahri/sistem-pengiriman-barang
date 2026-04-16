<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Enums\ShipmentStatus;

class ShipmentController extends Controller
{
    /**
     * Menampilkan daftar semua resi pengiriman
     */
    public function index()
    {
        // Mengambil data resi terbaru, 10 per halaman
        $shipments = Shipment::latest()->paginate(10);

        // Menghitung jumlah resi yang belum dijadwalkan (untuk badge notifikasi)
        $pendingCount = Shipment::pending()->count();

        return view('admin.shipments.index', compact('shipments', 'pendingCount'));
    }

    /**
     * Menampilkan halaman form untuk membuat resi baru
     */
    public function create()
    {
        // Mengambil data Master Rute untuk ditampilkan di Dropdown form nanti
        $shippingRates = ShippingRate::all();

        return view('admin.shipments.create', compact('shippingRates'));
    }

    public function show(Shipment $shipment)
    {
        return view('admin.shipments.show', compact('shipment'));
    }

    public function store(Request $request)
    {
        // 1. Validasi tetap wajib
        $validated = $request->validate([
            'sender_name'      => 'required|string|max:255',
            'sender_phone'     => 'required|string|max:20',
            'sender_address'   => 'required|string',
            'receiver_name'    => 'required|string|max:255',
            'receiver_phone'   => 'required|string|max:20',
            'receiver_address' => 'required|string',
            'origin_city'      => 'required|string',
            'destination_city' => 'required|string',
            'jalur_pengiriman' => 'required|string',
            'item_description' => 'required|string',
            'jumlah_koli'      => 'required|integer|min:1',
            'weight'           => 'required|numeric|min:0.1',
            'shipping_cost'    => 'required|numeric|min:0',
        ]);

        // 2. Logic Tambahan (Opsional tapi Pro):
        // Kita bisa melakukan re-kalkulasi di sini untuk memastikan
        // shipping_cost yang dikirim dari front-end tidak dimanipulasi.

        // 3. Generate No. Resi
        $trackingNumber = "KEN-" . date('Ymd') . "-" . strtoupper(Str::random(4));

        // 4. Simpan ke Database
        Shipment::create([
            'tracking_number'  => $trackingNumber,
            'sender_name'      => $validated['sender_name'],
            'sender_phone'     => $validated['sender_phone'],
            'sender_address'   => $validated['sender_address'],
            'receiver_name'    => $validated['receiver_name'],
            'receiver_phone'   => $validated['receiver_phone'],
            'receiver_address' => $validated['receiver_address'],
            'origin_city'      => $validated['origin_city'],
            'destination_city' => $validated['destination_city'],
            'jalur_pengiriman' => $validated['jalur_pengiriman'],
            'item_description' => $validated['item_description'],
            'jumlah_koli'      => $validated['jumlah_koli'],
            'weight'           => $validated['weight'],
            'shipping_cost'    => $validated['shipping_cost'],
            'current_status'   => ShipmentStatus::DIPROSES,
            'manifest_id'      => null,
        ]);


        return redirect()->route('shipments.index')
            ->with('success', "Resi $trackingNumber berhasil dibuat!");
    }
}
