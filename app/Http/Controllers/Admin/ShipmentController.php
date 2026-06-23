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
        // 1. Siapkan kerangka query utama untuk tabel Resi
        $query = Shipment::query();

        // 2. Terapkan filter pencarian berdasarkan status jika admin memilih dari dropdown
        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        // 3. Ambil data dengan pembagian halaman (pagination), diurutkan dari yang terbaru
        $shipments = $query->latest()->paginate(10);

        $pendingCount = Shipment::pending()->count();

        return view('admin.shipments.index', compact('shipments', 'pendingCount'));
    }

    public function create()
    {
        // 1. Ambil daftar kota asal yang unik dari master tarif untuk mengisi pilihan dropdown
        $origins = ShippingRate::select('origin_city')->distinct()->pluck('origin_city');
        return view('admin.shipments.create', compact('origins'));
    }

    /**
     * Menampilkan form edit (hanya jika resi belum dijadwalkan)
     */
    public function edit(Shipment $shipment)
    {
        // 1. Validasi keamanan: Tolak akses jika resi sudah dijadwalkan ke dalam manifest
        if ($shipment->manifest_id !== null) {
            return redirect()->route('shipments.index')
                ->withErrors("Akses ditolak! Resi $shipment->tracking_number sudah masuk jadwal pengantaran dan tidak bisa diedit.");
        }

        // 2. Ambil data kota asal yang tersedia di master tarif untuk form edit
        $origins = ShippingRate::select('origin_city')->distinct()->pluck('origin_city');

        return view('admin.shipments.edit', compact('shipment', 'origins'));
    }

    public function destroy($id)
    {
        // 1. Cari data resi berdasarkan ID
        $shipment = Shipment::findOrFail($id);

        $statusVal = $shipment->current_status->value ?? $shipment->current_status;

        // 2. Validasi keamanan: Hanya resi berstatus Diproses (belum masuk manifest) yang boleh dihapus
        if ($statusVal !== 'Diproses' || $shipment->manifest_id !== null) {
            return redirect()->route('shipments.index')->withErrors('Resi ini sudah dijadwalkan atau dalam perjalanan, tidak dapat dihapus!');
        }

        // 3. Eksekusi penghapusan data dari database
        $shipment->delete();

        return redirect()->route('shipments.index')->with('success', 'Resi berhasil dihapus.');
    }

    /**
     * Keluarkan resi yang berstatus Penundaan Pengiriman dari daftar penjadwalan secara permanen
     * (dipakai saat customer memilih dibuatkan resi baru daripada menjadwalkan ulang resi lama).
     */
    public function cancelPermanent(Shipment $shipment)
    {
        $statusVal = $shipment->current_status->value ?? $shipment->current_status;

        // 1. Hanya resi Penundaan Pengiriman yang manifest-nya sudah tidak aktif boleh dibatalkan permanen,
        //    cermin dari kondisi yang membuat resi ini muncul di halaman "Buat Jadwal" untuk dijadwalkan ulang.
        $manifestAktif = $shipment->manifest && in_array($shipment->manifest->status, ['Persiapan', 'Ditugaskan', 'Sedang Jalan']);

        if ($statusVal !== 'Penundaan Pengiriman' || $manifestAktif) {
            return back()->withErrors('Resi ini tidak bisa dibatalkan permanen.');
        }

        // 2. Ubah status menjadi Dibatalkan agar resi tidak lagi muncul di daftar resi yang bisa dijadwalkan
        $shipment->update(['current_status' => ShipmentStatus::DIBATALKAN]);

        return back()->with('success', "Resi {$shipment->tracking_number} berhasil dikeluarkan dari jadwal secara permanen.");
    }

    public function update(Request $request, Shipment $shipment)
    {
        // 1. Validasi keamanan: Cegah penyimpanan jika resi sudah masuk ke dalam jadwal manifest
        if ($shipment->manifest_id !== null) {
            return redirect()->route('shipments.index')->withErrors("Data gagal disimpan. Resi sudah dalam pengantaran.");
        }

        // 2. Validasi kelengkapan dan format input dari form
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
            'is_min_charge'    => 'nullable|boolean'
        ]);

        // 3. Cek ketersediaan rute pengiriman di dalam database master tarif
        $rate = ShippingRate::where('origin_city', $validated['origin_city'])
                            ->where('destination_city', $validated['destination_city'])
                            ->first();

        if (!$rate) {
            return back()->withInput()->withErrors('Rute pengiriman tidak ditemukan di Master Tarif.');
        }

        // 4. Tentukan berat yang dihitung berdasarkan aturan batas minimum tarif
        $isMinChargeActive = $request->has('is_min_charge');
        $chargeableWeight = ($isMinChargeActive && $validated['weight'] < 20) ? 20 : $validated['weight'];

        // 5. Hitung ulang total ongkos kirim di backend untuk menghindari manipulasi data dari frontend
        $expectedCost = $rate->cost_per_kg * $chargeableWeight;

        if (abs($expectedCost - $validated['shipping_cost']) > 1) {
            $validated['shipping_cost'] = $expectedCost;
        }

        // 6. Simpan seluruh perubahan data resi ke dalam database
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

        // 1. Mulai pembentukan query untuk mencari tarif berdasarkan kota asal yang dipilih
        $query = ShippingRate::where('origin_city', $origin);

        // 2. Terapkan filter pencarian nama kota tujuan jika ada input dari pengguna
        if ($search) {
            $query->where('destination_city', 'like', '%' . $search . '%');
        }

        // 3. Format hasil pencarian agar sesuai dengan struktur data yang dibutuhkan oleh dropdown Select2
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
        // 1. Cari informasi tarif spesifik berdasarkan kombinasi rute kota asal dan kota tujuan
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
        // 1. Validasi seluruh kelengkapan dan format input pengiriman dari pengguna
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
            'is_min_charge'    => 'nullable|boolean'
        ]);

        // 2. Verifikasi ketersediaan rute pada master data tarif
        $rate = ShippingRate::where('origin_city', $validated['origin_city'])
                            ->where('destination_city', $validated['destination_city'])
                            ->first();

        if (!$rate) {
            return back()->withInput()->withErrors('Rute pengiriman tidak ditemukan di Master Tarif.');
        }

        // 3. Tentukan berat pengiriman akhir dengan mempertimbangkan aturan batas minimum berat
        $isMinChargeActive = $request->has('is_min_charge');
        $chargeableWeight = ($isMinChargeActive && $validated['weight'] < 20) ? 20 : $validated['weight'];

        // 4. Lakukan kalkulasi ulang total biaya pengiriman untuk mencegah manipulasi data dari *frontend*
        $expectedCost = $rate->cost_per_kg * $chargeableWeight;

        if (abs($expectedCost - $validated['shipping_cost']) > 1) {
            $validated['shipping_cost'] = $expectedCost;
        }

        // 5. Hasilkan nomor pelacakan (resi) unik secara acak berdasarkan tanggal
        $trackingNumber = "KEN-" . date('Ymd') . "-" . strtoupper(Str::random(4));

        // 6. Simpan seluruh data pengiriman baru ke dalam database sebagai status Diproses
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
