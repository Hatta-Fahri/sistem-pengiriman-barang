<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CourierController extends Controller
{
    public function index()
    {
        // 1. Ambil daftar seluruh pengguna yang terdaftar dengan jabatan kurir untuk ditampilkan di tabel
        $couriers = User::where('role', 'kurir')->latest()->paginate(10);
        return view('admin.couriers.index', compact('couriers'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input kelengkapan data diri dan informasi akun kurir baru
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:6',
            'nik'        => 'required|numeric|unique:users,nik',
            'phone'      => 'required|string|max:20',
            'sim_number' => 'required|numeric',
            'sim_type'   => 'required|in:SIM A,SIM B1,SIM B1 Umum,SIM B2,SIM B2 Umum',
            'address'    => 'required|string',
            'status'     => 'required|in:Aktif,Cuti,Berhenti',
        ]);

        // 2. Enkripsi kata sandi kurir sebelum disimpan demi keamanan
        $validated['password'] = Hash::make($validated['password']);
        
        // 3. Tetapkan peran akun secara permanen sebagai kurir
        $validated['role'] = 'kurir';

        // 4. Cari kode unik kurir terakhir yang terdaftar di database untuk menentukan nomor urut selanjutnya
        $lastCourier = User::where('role', 'kurir')->whereNotNull('courier_code')->orderBy('id', 'desc')->first();
        if ($lastCourier) {
            $lastNumber = (int) substr($lastCourier->courier_code, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // 5. Hasilkan kode kurir baru (misal: KRR001) secara otomatis dengan panjang 3 digit angka
        $validated['courier_code'] = 'KRR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // 6. Simpan seluruh informasi akun kurir baru ke dalam database
        User::create($validated);

        return redirect()->back()->with('success', 'Akun kurir berhasil ditambahkan!');
    }

    public function update(Request $request, User $courier)
    {
        // 1. Cegah pengeditan secara paksa jika ID pengguna yang dituju ternyata bukan seorang kurir
        if ($courier->role !== 'kurir') abort(403, 'Aksi tidak diizinkan.');

        // 2. Validasi perubahan data diri, pastikan email atau NIK yang baru tidak digunakan oleh pengguna lain
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'email', 'max:255', Rule::unique('users')->ignore($courier->id)],
            'password'   => 'nullable|string|min:6',
            'nik'        => ['required', 'numeric', Rule::unique('users')->ignore($courier->id)],
            'phone'      => 'required|string|max:20',
            'sim_number' => 'required|numeric',
            'sim_type'   => 'required|in:SIM A,SIM B1,SIM B1 Umum,SIM B2,SIM B2 Umum',
            'address'    => 'required|string',
            'status'     => 'required|in:Aktif,Cuti,Berhenti',
        ]);

        // 3. Enkripsi ulang kata sandi hanya jika Admin memasukkan kata sandi baru (biarkan tetap sama jika form kosong)
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // 4. Simpan pembaruan data kurir ke database
        $courier->update($validated);

        return redirect()->back()->with('success', 'Data kurir berhasil diperbarui!');
    }

    public function destroy(User $courier)
    {
        // 1. Cegah penghapusan secara paksa jika ID pengguna yang dituju bukan seorang kurir
        if ($courier->role !== 'kurir') abort(403, 'Aksi tidak diizinkan.');

        // 2. Hapus (atau nonaktifkan/soft delete) akun kurir dari sistem
        $courier->delete();
        return redirect()->back()->with('success', 'Akun kurir berhasil dinonaktifkan!');
    }
}
