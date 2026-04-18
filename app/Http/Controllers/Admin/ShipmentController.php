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
    public function index(Request $request)
    {
        // Siapkan query utama
        $query = Shipment::query();

        // Cek jika admin memilih filter status di dropdown
        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        // Ambil data (paginate 10 per halaman, terbaru di atas)
        $shipments = $query->latest()->paginate(10);

        $pendingCount = Shipment::pending()->count();

        return view('admin.shipments.index', compact('shipments', 'pendingCount'));
    }

    public function create()
    {
        // Tetap kirimkan jika dibutuhkan, meski di view kita hidden asal 'Medan'
        $origins = ShippingRate::select('origin_city')->distinct()->pluck('origin_city');
        return view('admin.shipments.create', compact('origins'));
    }

    /**
     * Menampilkan form edit (hanya jika resi belum dijadwalkan)
     */
    public function edit(Shipment $shipment)
    {
        // SECURITY CHECK: Tolak jika resi sudah masuk manifest (sedang diantar/selesai)
        if ($shipment->manifest_id !== null) {
            return redirect()->route('shipments.index')
                ->withErrors("Akses ditolak! Resi $shipment->tracking_number sudah masuk jadwal pengantaran dan tidak bisa diedit.");
        }

        $origins = ShippingRate::select('origin_city')->distinct()->pluck('origin_city');

        return view('admin.shipments.edit', compact('shipment', 'origins'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        // SECURITY CHECK: Double cross-check saat di-submit
        if ($shipment->manifest_id !== null) {
            return redirect()->route('shipments.index')->withErrors("Data gagal disimpan. Resi sudah dalam pengantaran.");
        }

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
            'is_min_charge'    => 'nullable|boolean' // Toggle Switch Input
        ]);

        $rate = ShippingRate::where('origin_city', $validated['origin_city'])
                            ->where('destination_city', $validated['destination_city'])
                            ->first();

        if (!$rate) {
            return back()->withInput()->withErrors('Rute pengiriman tidak ditemukan di Master Tarif.');
        }

        // LOGIKA MINIMUM CHARGE DARI TOGGLE
        $isMinChargeActive = $request->has('is_min_charge');
        $chargeableWeight = ($isMinChargeActive && $validated['weight'] < 20) ? 20 : $validated['weight'];

        // Kalkulasi ulang di backend
        $expectedCost = $rate->cost_per_kg * $chargeableWeight;

        // Validasi keamanan
        if (abs($expectedCost - $validated['shipping_cost']) > 1) {
            $validated['shipping_cost'] = $expectedCost;
        }

        // Simpan Perubahan Data
        $shipment->update([
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
        ]);

        return redirect()->route('shipments.index')->with('success', "Resi {$shipment->tracking_number} berhasil diperbarui!");
    }

    /**
     * [AJAX] Cari Kota Tujuan & Format Tampilan Dropdown
     */
    public function ajaxDestinations(Request $request)
    {
        $origin = $request->origin_city;
        $search = $request->search;

        $query = ShippingRate::where('origin_city', $origin);

        if ($search) {
            $query->where('destination_city', 'like', '%' . $search . '%');
        }

        $destinations = $query->get()->map(function ($rate) {
            $formattedRate = number_format($rate->cost_per_kg, 0, ',', '.');
            return [
                'id' => $rate->destination_city,
                'text' => "{$rate->origin_city} ➔ {$rate->destination_city}  (Tarif Dasar: Rp {$formattedRate}/Kg)"
            ];
        });

        return response()->json(['results' => $destinations]);
    }

    /**
     * [AJAX] Ambil Harga per Kg berdasarkan Rute
     */
    public function ajaxRate(Request $request)
    {
        $rate = ShippingRate::where('origin_city', $request->origin_city)
                            ->where('destination_city', $request->destination_city)
                            ->first();

        if ($rate) {
            return response()->json([
                'success' => true,
                'cost_per_kg' => $rate->cost_per_kg,
                'jalur_pengiriman' => $rate->jalur_pengiriman
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function show(Shipment $shipment)
    {
        return view('admin.shipments.show', compact('shipment'));
    }

    public function store(Request $request)
    {
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
            'is_min_charge'    => 'nullable|boolean' // Toggle Switch Input
        ]);

        $rate = ShippingRate::where('origin_city', $validated['origin_city'])
                            ->where('destination_city', $validated['destination_city'])
                            ->first();

        if (!$rate) {
            return back()->withInput()->withErrors('Rute pengiriman tidak ditemukan di Master Tarif.');
        }

        // LOGIKA MINIMUM CHARGE DARI TOGGLE
        $isMinChargeActive = $request->has('is_min_charge');
        $chargeableWeight = ($isMinChargeActive && $validated['weight'] < 20) ? 20 : $validated['weight'];

        // Kalkulasi ulang di backend
        $expectedCost = $rate->cost_per_kg * $chargeableWeight;

        // Validasi keamanan
        if (abs($expectedCost - $validated['shipping_cost']) > 1) {
            $validated['shipping_cost'] = $expectedCost;
        }

        $trackingNumber = "KEN-" . date('Ymd') . "-" . strtoupper(Str::random(4));

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

        return redirect()->route('shipments.index')->with('success', "Resi $trackingNumber berhasil dibuat!");
    }
}
