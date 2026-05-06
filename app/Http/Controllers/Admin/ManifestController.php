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
        // 1. Ambil seluruh data manifest beserta informasi kurir dan kendaraannya untuk ditampilkan di tabel
        $manifests = Manifest::with(['courier', 'vehicle'])->latest()->paginate(10);
        return view('admin.manifests.index', compact('manifests'));
    }

    public function create()
    {
        // 1. Ambil daftar resi yang belum masuk jadwal manifest atau yang sedang mengalami penundaan
        $availableShipments = Shipment::whereNull('manifest_id')
                                      ->orWhere('current_status', 'Penundaan Pengiriman')
                                      ->get();

        // 2. Identifikasi kendaraan yang sedang dipakai dalam manifest aktif (Persiapan/Sedang Jalan)
        $assignedVehicleIds = Manifest::whereIn('status', ['Persiapan', 'Sedang Jalan'])
                                      ->whereNotNull('vehicle_id')
                                      ->pluck('vehicle_id');

        // 3. Saring daftar kendaraan agar hanya menampilkan armada yang berstatus Tersedia dan tidak sedang dipakai
        $availableVehicles = Vehicle::where('status', 'Tersedia')
                                    ->whereNotIn('id', $assignedVehicleIds)
                                    ->get();

        // 4. Identifikasi kurir yang saat ini sedang memiliki tugas di manifest aktif
        $assignedCourierIds = Manifest::whereIn('status', ['Persiapan', 'Sedang Jalan'])
                                      ->whereNotNull('courier_id')
                                      ->pluck('courier_id');

        // 5. Saring daftar kurir agar hanya menampilkan kurir Aktif yang sedang tidak memiliki tugas
        $availableCouriers = User::where('role', 'kurir')
                                 ->where('status', 'Aktif')
                                 ->whereNotIn('id', $assignedCourierIds)
                                 ->get();

        return view('admin.manifests.create', compact('availableShipments', 'availableVehicles', 'availableCouriers'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input pembuatan jadwal, pastikan minimal satu resi telah dipilih
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
                // 2. Hitung total berat seluruh resi yang dipilih
                $shipments = Shipment::whereIn('id', $request->shipment_ids)->get();
                $totalWeight = $shipments->sum('weight');
                $vehicle = Vehicle::findOrFail($request->vehicle_id);

                // 3. Batalkan proses jika muatan melebihi kapasitas maksimal kendaraan
                if ($totalWeight > $vehicle->capacity) {
                    throw new \Exception("Kapasitas overload! Total muatan " . number_format($totalWeight, 1) . " Kg melebihi kapasitas mobil.");
                }

                // 4. Buat data manifest baru di database dengan status awal Persiapan
                $manifest = Manifest::create([
                    'manifest_code'    => $this->generateManifestCode(),
                    'jalur_pengiriman' => $request->jalur_pengiriman,
                    'vehicle_id'       => $request->vehicle_id,
                    'courier_id'       => $request->courier_id,
                    'total_weight'     => $totalWeight,
                    'total_shipments'  => $shipments->count(),
                    'notes'            => $request->notes,
                    'status'           => 'Persiapan',
                ]);

                // 5. Tautkan semua resi pilihan ke manifest ini dan ubah statusnya menjadi Diproses (kecuali yang tertunda)
                foreach ($shipments as $shipment) {
                    $statusVal = $shipment->current_status->value ?? $shipment->current_status;
                    if ($statusVal !== 'Penundaan Pengiriman') {
                        $shipment->current_status = 'Diproses';
                    }
                    $shipment->manifest_id = $manifest->id;
                    $shipment->save();
                }

                // 6. Ubah status kendaraan menjadi Terjadwal agar tidak bisa ditarik oleh manifest lain
                $vehicle->update(['status' => 'Terjadwal']);
            });

            return redirect()->route('manifests.index')->with('success', 'Jadwal & Muatan berhasil disimpan! Siap diberangkatkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

    public function edit(Manifest $manifest)
    {
        // 1. Cegah pengeditan jika manifest sudah diberangkatkan
        if ($manifest->status !== 'Persiapan') {
            return redirect()->route('manifests.index')->withErrors('Hanya jadwal Persiapan yang bisa diedit.');
        }

        // 2. Ambil daftar resi yang bebas tugas, tertunda, atau memang sudah berada di dalam manifest ini
        $availableShipments = Shipment::whereNull('manifest_id')
                                      ->orWhere('manifest_id', $manifest->id)
                                      ->orWhere('current_status', 'Penundaan Pengiriman')
                                      ->get();

        // 3. Cari daftar kendaraan yang tersedia dan pastikan kendaraan di manifest ini saat ini tetap bisa dipilih
        $otherAssignedVehicles = Manifest::whereIn('status', ['Persiapan', 'Sedang Jalan'])
                                         ->where('id', '!=', $manifest->id)
                                         ->whereNotNull('vehicle_id')
                                         ->pluck('vehicle_id');

        $availableVehicles = Vehicle::where('status', 'Tersedia')
                                    ->whereNotIn('id', $otherAssignedVehicles)
                                    ->orWhere('id', $manifest->vehicle_id)
                                    ->get();

        // 4. Cari daftar kurir yang sedang luang, dan pastikan kurir pada manifest ini tetap muncul di opsi
        $otherAssignedCouriers = Manifest::whereIn('status', ['Persiapan', 'Sedang Jalan'])
                                         ->where('id', '!=', $manifest->id)
                                         ->whereNotNull('courier_id')
                                         ->pluck('courier_id');

        $availableCouriers = User::where('role', 'kurir')
                                 ->where('status', 'Aktif')
                                 ->whereNotIn('id', $otherAssignedCouriers)
                                 ->orWhere('id', $manifest->courier_id)
                                 ->get();

        return view('admin.manifests.edit', compact('manifest', 'availableShipments', 'availableVehicles', 'availableCouriers'));
    }

    public function update(Request $request, Manifest $manifest)
    {
        // 1. Tolak aksi pembaruan jika status manifest sudah terlanjur jalan
        if ($manifest->status !== 'Persiapan') {
            return back()->withErrors('Tidak bisa mengedit jadwal yang sudah berangkat.');
        }

        // 2. Validasi kelengkapan form
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
                // 3. Hitung ulang total berat paket untuk mencegah kelebihan muatan
                $shipments = Shipment::whereIn('id', $request->shipment_ids)->get();
                $totalWeight = $shipments->sum('weight');
                $vehicle = Vehicle::findOrFail($request->vehicle_id);

                if ($totalWeight > $vehicle->capacity) {
                    throw new \Exception("Kapasitas overload!");
                }

                // 4. Jika kendaraan diubah, bebaskan kendaraan lama menjadi Tersedia kembali
                if ($manifest->vehicle_id && $manifest->vehicle_id != $request->vehicle_id) {
                    Vehicle::where('id', $manifest->vehicle_id)->update(['status' => 'Tersedia']);
                }

                // 5. Kunci kendaraan baru dengan status Terjadwal
                $vehicle->update(['status' => 'Terjadwal']);

                // 6. Lepaskan ikatan semua resi lama dari manifest ini agar kondisinya bersih
                $oldShipments = Shipment::where('manifest_id', $manifest->id)->get();
                foreach ($oldShipments as $old) {
                    $val = $old->current_status->value ?? $old->current_status;
                    if ($val !== 'Penundaan Pengiriman') {
                        $old->current_status = 'Diproses';
                    }
                    $old->manifest_id = null;
                    $old->save();
                }

                // 7. Masukkan dan tautkan kembali daftar resi pilihan baru ke dalam manifest ini
                foreach ($shipments as $newShip) {
                    $val = $newShip->current_status->value ?? $newShip->current_status;
                    if ($val !== 'Penundaan Pengiriman') {
                        $newShip->current_status = 'Diproses';
                    }
                    $newShip->manifest_id = $manifest->id;
                    $newShip->save();
                }

                // 8. Simpan seluruh pembaruan atribut manifest ke database
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

    public function show(Manifest $manifest)
    {
        $manifest->load(['shipments', 'vehicle', 'courier']);
        return view('admin.manifests.show', compact('manifest'));
    }

    public function berangkatkan(Manifest $manifest)
    {
        DB::transaction(function () use ($manifest) {
            // 1. Ubah status manifest menjadi Sedang Jalan agar kurir menerima tugasnya
            $manifest->update(['status' => 'Sedang Jalan']);

            // 2. Ubah status armada menjadi Sedang Digunakan
            if ($manifest->vehicle) {
                $manifest->vehicle->update(['status' => 'Sedang Digunakan']);
            }
        });

        return back()->with('success', 'Tugas berhasil diteruskan ke aplikasi Kurir!');
    }

    public function destroy(Manifest $manifest)
    {
        // 1. Lepaskan seluruh ikatan resi dari manifest ini dan kembalikan statusnya ke awal (Diproses)
        $shipments = Shipment::where('manifest_id', $manifest->id)->get();
        foreach ($shipments as $shipment) {
            $val = $shipment->current_status->value ?? $shipment->current_status;
            if ($val !== 'Penundaan Pengiriman') {
                $shipment->current_status = 'Diproses';
            }
            $shipment->manifest_id = null;
            $shipment->save();
        }

        // 2. Kembalikan status kendaraan menjadi Tersedia agar bisa dipakai oleh jadwal lain
        if ($manifest->vehicle_id) {
            Vehicle::where('id', $manifest->vehicle_id)->update(['status' => 'Tersedia']);
        }

        // 3. Hapus data manifest secara permanen
        $manifest->delete();
        return back()->with('success', 'Jadwal dihapus, resi dikembalikan ke gudang.');
    }

    private function generateManifestCode(): string
    {
        // 1. Buat kode manifest unik berdasarkan tanggal hari ini dan urutan nomor berlanjut
        $datePrefix = 'MAN-' . now()->format('Ymd') . '-';
        $lastManifest = Manifest::withoutGlobalScopes()->where('manifest_code', 'like', $datePrefix . '%')->orderBy('manifest_code', 'desc')->first();
        $newSequence = $lastManifest ? str_pad((string)((int) substr($lastManifest->manifest_code, -3) + 1), 3, '0', STR_PAD_LEFT) : '001';
        return $datePrefix . $newSequence;
    }
}
