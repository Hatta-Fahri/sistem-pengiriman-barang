<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    public function index()
    {
        // 1. Ambil daftar tarif rute pengiriman dari database secara terurut dari data terbaru
        $rates = ShippingRate::latest()->paginate(10);
        return view('admin.shipping_rates.index', compact('rates'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input nama kota tujuan, asal, jalur pengiriman, dan harga tarif baru
        $validated = $request->validate([
            'origin_city'           => 'required|string|max:255',
            'destination_city'      => 'required|string|max:255',
            'jalur_pengiriman'      => 'required|string|max:255',
            'cost_per_kg'           => 'required|numeric|min:0',
            'estimated_distance_km' => 'nullable|numeric|min:0',
        ]);

        // 2. Format penulisan nama kota agar selalu menggunakan huruf kapital di awal kata (misal: "medan" -> "Medan")
        $origin = ucwords(strtolower($validated['origin_city']));
        $destination = ucwords(strtolower($validated['destination_city']));

        // 3. Verifikasi apakah kombinasi rute kota asal dan tujuan ini sudah pernah didaftarkan
        $exists = ShippingRate::where('origin_city', $origin)
            ->where('destination_city', $destination)
            ->exists();

        // 4. Tolak penyimpanan jika rute sudah terdaftar di sistem
        if ($exists) {
            return back()->with('error', "Rute $origin ke $destination sudah ada di sistem!");
        }

        // 5. Simpan data tarif rute baru ke dalam database
        ShippingRate::create([
            'origin_city'           => $origin,
            'destination_city'      => $destination,
            'jalur_pengiriman'      => $validated['jalur_pengiriman'],
            'cost_per_kg'           => $validated['cost_per_kg'],
            'estimated_distance_km' => $validated['estimated_distance_km'],
        ]);

        return back()->with('success', 'Rute dan tarif berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // 1. Cari data tarif pengiriman berdasarkan ID
        $rate = ShippingRate::findOrFail($id);

        // 2. Validasi input perubahan jalur pengiriman, harga tarif, dan estimasi jarak
        $validated = $request->validate([
            'jalur_pengiriman'      => 'required|string|max:255',
            'cost_per_kg'           => 'required|numeric|min:0',
            'estimated_distance_km' => 'nullable|numeric|min:0',
        ]);

        // 3. Simpan perubahan tarif ke dalam database
        $rate->update($validated);

        return back()->with('success', 'Tarif pengiriman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // 1. Cari rute pengiriman berdasarkan ID dan hapus dari database secara permanen
        ShippingRate::findOrFail($id)->delete();
        return back()->with('success', 'Rute berhasil dihapus.');
    }
}
