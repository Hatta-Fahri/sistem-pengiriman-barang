<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manifest;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManifestController extends Controller
{
    public function index()
    {
        $manifests = Manifest::with(['courier', 'vehicle'])->latest()->paginate(10);
        return view('admin.manifests.index', compact('manifests'));
    }

    /**
     * Tampilkan Halaman Buat Jadwal (One-Stop Dispatch)
     */
    public function create()
    {
        // Hanya ambil resi yang belum masuk manifest manapun
        $availableShipments = Shipment::whereNull('manifest_id')->get();
        $availableVehicles = Vehicle::where('status', 'Tersedia')->get();
        $availableCouriers = User::where('role', 'kurir')->where('status', 'Aktif')->get();

        return view('admin.manifests.create', compact('availableShipments', 'availableVehicles', 'availableCouriers'));
    }

    /**
     * Simpan Jadwal Sekaligus Muatan Resi-nya
     */
    public function store(Request $request)
    {
        $request->validate([
            'jalur_pengiriman' => 'required|string|max:255',
            'notes'            => 'nullable|string',
            'shipment_ids'     => 'required|array',
            'shipment_ids.*'   => 'exists:shipments,id',
            'vehicle_id'       => 'required|exists:vehicles,id',
            'courier_id'       => 'required|exists:users,id',
        ], [
            'shipment_ids.required' => 'Pilih minimal satu resi barang untuk dimuat!',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Hitung total berat
                $shipments = Shipment::whereIn('id', $request->shipment_ids)->get();
                $totalWeight = $shipments->sum('weight');

                $vehicle = Vehicle::findOrFail($request->vehicle_id);

                // Validasi Kapasitas
                if ($totalWeight > $vehicle->capacity) {
                    throw new \Exception("Kapasitas overload! Total muatan " . number_format($totalWeight, 1) . " Kg melebihi kapasitas mobil.");
                }

                // 1. Buat Manifest Baru (Status: Persiapan / Ready)
                $manifest = Manifest::create([
                    'manifest_code'    => $this->generateManifestCode(),
                    'jalur_pengiriman' => $request->jalur_pengiriman,
                    'vehicle_id'       => $request->vehicle_id,
                    'courier_id'       => $request->courier_id,
                    'total_weight'     => $totalWeight,
                    'total_shipments'  => $shipments->count(),
                    'notes'            => $request->notes,
                    'status'           => 'Persiapan', // Atau 'Ready' sesuai kemauanmu
                ]);

                // 2. Masukkan Resi ke Manifest & Ubah Statusnya menjadi 'Diproses' atau 'Ready'
                Shipment::whereIn('id', $request->shipment_ids)->update([
                    'manifest_id'    => $manifest->id,
                    'current_status' => 'Diproses' // Sesuaikan jika Enum-mu bernama 'Ready'
                ]);

                // 3. Ubah status Mobil dan Kurir
                $vehicle->update(['status' => 'Terjadwal']); // Ubah sesuai status MySQL kamu
            });

            return redirect()->route('manifests.index')->with('success', 'Jadwal & Muatan berhasil disimpan! Siap diberangkatkan.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

    /**
     * Halaman Edit Jadwal (Hanya untuk status Persiapan)
     */
    public function edit(Manifest $manifest)
    {
        if ($manifest->status !== 'Persiapan') {
            return redirect()->route('manifests.index')->withErrors('Hanya jadwal Persiapan yang bisa diedit.');
        }

        // Ambil resi yang belum ada jadwal + resi yang SEDANG ada di jadwal ini
        $availableShipments = Shipment::whereNull('manifest_id')
                                      ->orWhere('manifest_id', $manifest->id)
                                      ->get();

        $availableVehicles = Vehicle::where('status', 'Tersedia')
                                    ->orWhere('id', $manifest->vehicle_id)
                                    ->get();

        $availableCouriers = User::where('role', 'kurir')
                                 ->where('status', 'Aktif')
                                 ->orWhere('id', $manifest->courier_id)
                                 ->get();

        return view('admin.manifests.edit', compact('manifest', 'availableShipments', 'availableVehicles', 'availableCouriers'));
    }

    /**
     * Simpan Perubahan Jadwal & Muatan
     */
    public function update(Request $request, Manifest $manifest)
    {
        if ($manifest->status !== 'Persiapan') {
            return back()->withErrors('Tidak bisa mengedit jadwal yang sudah berangkat.');
        }

        $request->validate([
            'jalur_pengiriman' => 'required|string|max:255',
            'notes'            => 'nullable|string',
            'shipment_ids'     => 'required|array',
            'shipment_ids.*'   => 'exists:shipments,id',
            'vehicle_id'       => 'required|exists:vehicles,id',
            'courier_id'       => 'required|exists:users,id',
        ]);

        try {
            DB::transaction(function () use ($request, $manifest) {
                $shipments = Shipment::whereIn('id', $request->shipment_ids)->get();
                $totalWeight = $shipments->sum('weight');

                $vehicle = Vehicle::findOrFail($request->vehicle_id);

                if ($totalWeight > $vehicle->capacity) {
                    throw new \Exception("Kapasitas overload!");
                }

                // Kembalikan status kendaraan lama jika diganti
                if ($manifest->vehicle_id && $manifest->vehicle_id != $request->vehicle_id) {
                    Vehicle::where('id', $manifest->vehicle_id)->update(['status' => 'Tersedia']);
                }

                $vehicle->update(['status' => 'Terjadwal']);

                // 1. Lepas semua resi lama dari manifest ini
                Shipment::where('manifest_id', $manifest->id)->update(['manifest_id' => null, 'current_status' => 'Diproses']);

                // 2. Masukkan resi baru hasil editan
                Shipment::whereIn('id', $request->shipment_ids)->update(['manifest_id' => $manifest->id, 'current_status' => 'Diproses']);

                // 3. Update data manifest
                $manifest->update([
                    'jalur_pengiriman' => $request->jalur_pengiriman,
                    'vehicle_id'       => $request->vehicle_id,
                    'courier_id'       => $request->courier_id,
                    'total_weight'     => $totalWeight,
                    'total_shipments'  => $shipments->count(),
                    'notes'            => $request->notes,
                ]);
            });

            return redirect()->route('manifests.index')->with('success', 'Jadwal dan muatan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Halaman Detail Read-Only (Untuk status Sedang Jalan & Selesai)
     */
    public function show(Manifest $manifest)
    {
        $manifest->load(['shipments', 'vehicle', 'courier']);
        return view('admin.manifests.show', compact('manifest'));
    }

    public function berangkatkan(Manifest $manifest)
    {
        DB::transaction(function () use ($manifest) {
            $manifest->update(['status' => 'Sedang Jalan', 'departed_at' => now()]);
            Shipment::where('manifest_id', $manifest->id)->update(['current_status' => 'Dalam Pengantaran']);
            if ($manifest->vehicle) $manifest->vehicle->update(['status' => 'Sedang Digunakan']);
        });

        return back()->with('success', 'Truk diberangkatkan!');
    }

    public function destroy(Manifest $manifest)
    {
        Shipment::where('manifest_id', $manifest->id)->update(['manifest_id' => null, 'current_status' => 'Diproses']);
        $manifest->delete();
        return back()->with('success', 'Jadwal dihapus, resi dikembalikan ke gudang.');
    }

    private function generateManifestCode(): string
    {
        $datePrefix = 'MAN-' . now()->format('Ymd') . '-';
        $lastManifest = Manifest::withoutGlobalScopes()->where('manifest_code', 'like', $datePrefix . '%')->orderBy('manifest_code', 'desc')->first();
        $newSequence = $lastManifest ? str_pad((string)((int) substr($lastManifest->manifest_code, -3) + 1), 3, '0', STR_PAD_LEFT) : '001';
        return $datePrefix . $newSequence;
    }
}
