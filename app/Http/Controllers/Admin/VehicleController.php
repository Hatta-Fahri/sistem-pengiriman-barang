<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Menampilkan daftar armada kendaraan.
     */
    public function index()
    {
        // 1. Ambil daftar kendaraan armada dari database, diurutkan dari yang terbaru, dan terapkan pembagian halaman (pagination)
        $vehicles = Vehicle::latest()->paginate(10);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Menyimpan data armada baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input form penambahan armada untuk memastikan plat nomor unik dan data kapasitas terisi benar
        $validated = $request->validate([
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate',
            'type'          => 'required|string|max:50',
            'capacity'      => 'required|numeric|min:1',
        ]);

        // 2. Format plat nomor menjadi huruf kapital semua agar seragam di database
        $validated['license_plate'] = strtoupper($validated['license_plate']);

        // 3. Tetapkan status kendaraan baru secara paksa menjadi 'Tersedia'
        $validated['status'] = 'Tersedia';

        // 4. Simpan seluruh data kendaraan baru ke dalam database
        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Armada kendaraan berhasil ditambahkan!');
    }


    public function update(Request $request, Vehicle $vehicle)
    {
        // 1. Validasi pembaruan data armada, pastikan plat nomor baru tidak bentrok dengan armada lain
        $validated = $request->validate([
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicle->id,
            'type'          => 'required|string|max:50',
            'capacity'      => 'required|numeric|min:1',
            'status'        => 'required|in:Tersedia,Sedang Jalan,Maintenance',
        ]);

        // 2. Format ulang plat nomor menjadi huruf kapital
        $validated['license_plate'] = strtoupper($validated['license_plate']);

        // 3. Simpan perubahan data kendaraan ke database
        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Data armada berhasil diperbarui!');
    }

    /**
     * Menghapus (Soft Delete) armada dari database.
     */
    public function destroy(Vehicle $vehicle)
    {
        // 1. Hapus armada kendaraan dari sistem (menggunakan Soft Delete agar riwayat manifest lama tidak rusak)
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Armada kendaraan berhasil dihapus!');
    }
}
