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
        // Mengambil data terbaru, dengan pagination agar query tidak berat
        $vehicles = Vehicle::latest()->paginate(10);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Menyimpan data armada baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi HANYA untuk input yang dikirim dari form
        $validated = $request->validate([
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate',
            'type'          => 'required|string|max:50',
            'capacity'      => 'required|numeric|min:1',
            // Kita hapus validasi 'status' di sini karena form tambah tidak mengirimkannya
        ]);

        // 2. Format & Set Nilai Default
        $validated['license_plate'] = strtoupper($validated['license_plate']);

        // Memaksa status menjadi 'Tersedia' secara otomatis untuk kendaraan baru
        $validated['status'] = 'Tersedia';

        // 3. Simpan ke database
        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Armada kendaraan berhasil ditambahkan!');
    }

    /**
     * Memperbarui data armada yang sudah ada.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        // 1. Validasi Ketat (Mengecualikan plat nomor milik kendaraan ini sendiri)
        $validated = $request->validate([
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicle->id,
            'type'          => 'required|string|max:50',
            'capacity'      => 'required|numeric|min:1',
            'status'        => 'required|in:Tersedia,Sedang Jalan,Maintenance',
        ]);

        // 2. Format plat nomor
        $validated['license_plate'] = strtoupper($validated['license_plate']);

        // 3. Update database
        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Data armada berhasil diperbarui!');
    }

    /**
     * Menghapus (Soft Delete) armada dari database.
     */
    public function destroy(Vehicle $vehicle)
    {
        // Catatan: Karena di Model kita pakai SoftDeletes, data tidak akan benar-benar
        // hilang dari database (bagus untuk audit trail jika truk ini pernah dipakai di manifest lama).

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Armada kendaraan berhasil dihapus!');
    }
}
