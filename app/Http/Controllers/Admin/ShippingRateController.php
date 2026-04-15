<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    // Menampilkan daftar rute dan tarif
    public function index()
    {
        // Mengambil data terbaru, dibatasi 10 per halaman (Pagination)
        $rates = ShippingRate::latest()->paginate(10);
        return view('admin.shipping_rates.index', compact('rates'));
    }

    // Menyimpan rute baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin_city'           => 'required|string|max:255',
            'destination_city'      => 'required|string|max:255',
            'cost_per_kg'           => 'required|numeric|min:0',
            'estimated_distance_km' => 'nullable|numeric|min:0',
        ]);

        // Standarisasi teks (misal: "medan" -> "Medan")
        $origin = ucwords(strtolower($validated['origin_city']));
        $destination = ucwords(strtolower($validated['destination_city']));

        // Cek manual apakah rute sudah ada agar bisa memberi pesan error yang rapi
        $exists = ShippingRate::where('origin_city', $origin)
                              ->where('destination_city', $destination)
                              ->exists();

        if ($exists) {
            return back()->with('error', "Rute $origin ke $destination sudah ada di sistem!");
        }

        // Jika aman, simpan ke database
        ShippingRate::create([
            'origin_city'           => $origin,
            'destination_city'      => $destination,
            'cost_per_kg'           => $validated['cost_per_kg'],
            'estimated_distance_km' => $validated['estimated_distance_km'],
        ]);

        return back()->with('success', 'Rute dan tarif berhasil ditambahkan.');
    }

    // Memperbarui tarif (Admin biasanya hanya perlu update harga/jarak, bukan nama kotanya)
    public function update(Request $request, $id)
    {
        $rate = ShippingRate::findOrFail($id);

        $validated = $request->validate([
            'cost_per_kg'           => 'required|numeric|min:0',
            'estimated_distance_km' => 'nullable|numeric|min:0',
        ]);

        $rate->update($validated);

        return back()->with('success', 'Tarif pengiriman berhasil diperbarui.');
    }

    // Menghapus rute
    public function destroy($id)
    {
        ShippingRate::findOrFail($id)->delete();
        return back()->with('success', 'Rute berhasil dihapus.');
    }
}
