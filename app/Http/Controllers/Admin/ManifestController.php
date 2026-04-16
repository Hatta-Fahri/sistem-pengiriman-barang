<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manifest;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Vehicle;
use App\Enums\ShipmentStatus; // Wajib import Enum
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManifestController extends Controller
{
    /**
     * Menampilkan daftar Penjadwalan (Manifest)
     */
    public function index()
    {
        // Eager loading untuk mencegah N+1 Query
        $manifests = Manifest::with(['courier', 'vehicle'])->latest()->paginate(10);

        // Data untuk Dropdown Modal Draft
        $couriers = User::where('role', 'kurir')->where('status', 'Aktif')->get();
        $vehicles = Vehicle::where('status', 'Tersedia')->get();

        return view('admin.manifests.index', compact('manifests', 'couriers', 'vehicles'));
    }

    /**
     * Menyimpan data Manifest (Draft / Rute Baru)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jalur_pengiriman' => 'required|string|max:255',
            'courier_id'       => 'nullable|exists:users,id',
            'vehicle_id'       => 'nullable|exists:vehicles,id',
            'notes'            => 'nullable|string',
        ]);

        // Auto-Generate Kode (MAN-YYYYMMDD-001)
        $datePrefix = 'MAN-' . now()->format('Ymd') . '-';
        $lastManifest = Manifest::where('manifest_code', 'like', $datePrefix . '%')
                                ->orderBy('manifest_code', 'desc')
                                ->first();

        $newSequence = $lastManifest ? str_pad((int) substr($lastManifest->manifest_code, -3) + 1, 3, '0', STR_PAD_LEFT) : '001';

        $validated['manifest_code'] = $datePrefix . $newSequence;
        $validated['status'] = 'Draft';

        Manifest::create($validated);

        return redirect()->back()->with('success', 'Jadwal berhasil dibuat! Silakan klik tombol Muat Barang untuk mengatur keberangkatan.');
    }

    /**
     * Halaman Persiapan / Dispatch Room (Pilih Resi, Mobil, Kurir)
     */
    public function show(Manifest $manifest)
    {
        // Cegah akses form jika manifest sudah berangkat
        if (in_array($manifest->status, ['Sedang Jalan', 'Selesai'])) {
            return redirect()->route('manifests.index')->withErrors('Manifest ini sudah diberangkatkan atau selesai.');
        }

        // Menggunakan scopePending() dari Model Shipment (Status: DIPROSES)
        $availableShipments = Shipment::pending()->get();

        $availableVehicles = Vehicle::where('status', 'Tersedia')->get();
        $availableCouriers = User::where('role', 'kurir')->where('status', 'Aktif')->get();

        return view('admin.manifests.show', compact('manifest', 'availableShipments', 'availableVehicles', 'availableCouriers'));
    }

    /**
     * Memproses Keberangkatan (Generate)
     */
    public function generate(Request $request, Manifest $manifest)
    {
        $request->validate([
            'shipment_ids'   => 'required|array',
            'shipment_ids.*' => 'exists:shipments,id',
            'vehicle_id'     => 'required|exists:vehicles,id',
            'courier_id'     => 'required|exists:users,id',
        ], [
            'shipment_ids.required' => 'Pilih minimal satu resi barang untuk diberangkatkan!',
            'vehicle_id.required'   => 'Pilih armada/kendaraan yang akan digunakan!',
            'courier_id.required'   => 'Pilih kurir/supir yang akan bertugas!',
        ]);

        try {
            DB::transaction(function () use ($request, $manifest) {

                $shipments = Shipment::whereIn('id', $request->shipment_ids)->get();
                $totalWeight = $shipments->sum('weight');

                $vehicle = Vehicle::findOrFail($request->vehicle_id);

                // Validasi Kapasitas Ekstra Ketat
                if ($totalWeight > $vehicle->capacity) {
                    throw new \Exception("Kapasitas overload! Total muatan " . number_format($totalWeight, 1) . " Kg melebihi kapasitas mobil (" . number_format($vehicle->capacity, 0) . " Kg).");
                }

                // 1. Update Tabel Manifest
                $manifest->update([
                    'vehicle_id'      => $request->vehicle_id,
                    'courier_id'      => $request->courier_id,
                    'total_weight'    => $totalWeight,
                    'total_shipments' => $shipments->count(),
                    'status'          => 'Sedang Jalan',
                    'departed_at'     => now(),
                ]);

                // 2. Update Status Resi & Manifest ID (MENGGUNAKAN ENUM)
                Shipment::whereIn('id', $request->shipment_ids)->update([
                    'manifest_id'    => $manifest->id,
                    'current_status' => ShipmentStatus::DALAM_PENGANTARAN->value
                ]);

                // 3. Update Status Kendaraan (Sesuai Enum Vehicle kamu)
                $vehicle->update(['status' => 'Sedang Jalan']);

                // (Status Kurir tidak diubah karena Enum Kurir adalah status kepegawaian Aktif/Cuti/Berhenti)
            });

            return redirect()->route('manifests.index')->with('success', 'Jadwal berhasil di-generate! Armada mulai diberangkatkan.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Memperbarui rute / catatan (Hanya jika masih Draft)
     */
    public function update(Request $request, Manifest $manifest)
    {
        $validated = $request->validate([
            'jalur_pengiriman' => 'required|string|max:255',
            'notes'            => 'nullable|string',
        ]);
        $manifest->update($validated);
        return redirect()->back()->with('success', 'Rute manifest berhasil diperbarui!');
    }

    /**
     * Hapus Manifest (Soft Delete)
     */
    public function destroy(Manifest $manifest)
    {
        if ($manifest->status === 'Sedang Jalan') {
            return redirect()->back()->withErrors('Tidak bisa menghapus jadwal yang sedang berjalan!');
        }
        $manifest->delete();
        return redirect()->back()->with('success', 'Jadwal Manifest berhasil dihapus.');
    }
}
