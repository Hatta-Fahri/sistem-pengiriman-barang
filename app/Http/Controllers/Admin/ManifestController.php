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

    public function create()
    {
        $availableShipments = Shipment::whereNull('manifest_id')
                                      ->orWhere('current_status', 'Penundaan Pengiriman')
                                      ->get();

        $assignedVehicleIds = Manifest::whereIn('status', ['Persiapan', 'Sedang Jalan'])
                                      ->whereNotNull('vehicle_id')
                                      ->pluck('vehicle_id');

        $availableVehicles = Vehicle::where('status', 'Tersedia')
                                    ->whereNotIn('id', $assignedVehicleIds)
                                    ->get();

        $assignedCourierIds = Manifest::whereIn('status', ['Persiapan', 'Sedang Jalan'])
                                      ->whereNotNull('courier_id')
                                      ->pluck('courier_id');

        $availableCouriers = User::where('role', 'kurir')
                                 ->where('status', 'Aktif')
                                 ->whereNotIn('id', $assignedCourierIds)
                                 ->get();

        return view('admin.manifests.create', compact('availableShipments', 'availableVehicles', 'availableCouriers'));
    }

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
                $shipments = Shipment::whereIn('id', $request->shipment_ids)->get();
                $totalWeight = $shipments->sum('weight');
                $vehicle = Vehicle::findOrFail($request->vehicle_id);

                if ($totalWeight > $vehicle->capacity) {
                    throw new \Exception("Kapasitas overload! Total muatan " . number_format($totalWeight, 1) . " Kg melebihi kapasitas mobil.");
                }

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

                // 👇 LOGIKA BARU: Jangan reset paket yang sedang ditunda
                foreach ($shipments as $shipment) {
                    $statusVal = $shipment->current_status->value ?? $shipment->current_status;
                    if ($statusVal !== 'Penundaan Pengiriman') {
                        $shipment->current_status = 'Diproses';
                    }
                    $shipment->manifest_id = $manifest->id;
                    $shipment->save();
                }

                $vehicle->update(['status' => 'Terjadwal']);
            });

            return redirect()->route('manifests.index')->with('success', 'Jadwal & Muatan berhasil disimpan! Siap diberangkatkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

    public function edit(Manifest $manifest)
    {
        if ($manifest->status !== 'Persiapan') {
            return redirect()->route('manifests.index')->withErrors('Hanya jadwal Persiapan yang bisa diedit.');
        }

        $availableShipments = Shipment::whereNull('manifest_id')
                                      ->orWhere('manifest_id', $manifest->id)
                                      ->orWhere('current_status', 'Penundaan Pengiriman')
                                      ->get();

        $otherAssignedVehicles = Manifest::whereIn('status', ['Persiapan', 'Sedang Jalan'])
                                         ->where('id', '!=', $manifest->id)
                                         ->whereNotNull('vehicle_id')
                                         ->pluck('vehicle_id');

        $availableVehicles = Vehicle::where('status', 'Tersedia')
                                    ->whereNotIn('id', $otherAssignedVehicles)
                                    ->orWhere('id', $manifest->vehicle_id)
                                    ->get();

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

                if ($manifest->vehicle_id && $manifest->vehicle_id != $request->vehicle_id) {
                    Vehicle::where('id', $manifest->vehicle_id)->update(['status' => 'Tersedia']);
                }

                $vehicle->update(['status' => 'Terjadwal']);

                // Lepas resi lama
                $oldShipments = Shipment::where('manifest_id', $manifest->id)->get();
                foreach ($oldShipments as $old) {
                    $val = $old->current_status->value ?? $old->current_status;
                    if ($val !== 'Penundaan Pengiriman') {
                        $old->current_status = 'Diproses';
                    }
                    $old->manifest_id = null;
                    $old->save();
                }

                // Masukkan resi baru
                foreach ($shipments as $newShip) {
                    $val = $newShip->current_status->value ?? $newShip->current_status;
                    if ($val !== 'Penundaan Pengiriman') {
                        $newShip->current_status = 'Diproses';
                    }
                    $newShip->manifest_id = $manifest->id;
                    $newShip->save();
                }

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
            $manifest->update(['status' => 'Sedang Jalan']);

            if ($manifest->vehicle) {
                $manifest->vehicle->update(['status' => 'Sedang Digunakan']);
            }
        });

        return back()->with('success', 'Tugas berhasil diteruskan ke aplikasi Kurir!');
    }

    public function destroy(Manifest $manifest)
    {
        $shipments = Shipment::where('manifest_id', $manifest->id)->get();
        foreach ($shipments as $shipment) {
            $val = $shipment->current_status->value ?? $shipment->current_status;
            if ($val !== 'Penundaan Pengiriman') {
                $shipment->current_status = 'Diproses';
            }
            $shipment->manifest_id = null;
            $shipment->save();
        }

        if ($manifest->vehicle_id) {
            Vehicle::where('id', $manifest->vehicle_id)->update(['status' => 'Tersedia']);
        }

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
