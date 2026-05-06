<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil filter role — default ke 'admin' jika tidak ada query string
        $roleFilter = $request->get('role', 'admin');

        // 2. Bangun query berdasarkan role yang dipilih
        $users = User::where('role', $roleFilter)->latest()->paginate(15)->withQueryString();

        // 3. Hitung total tiap role untuk badge di tab filter
        $totalAdmin = User::where('role', 'admin')->count();
        $totalKurir = User::where('role', 'kurir')->count();

        return view('admin.users.index', compact('users', 'roleFilter', 'totalAdmin', 'totalKurir'));
    }

    public function store(Request $request)
    {
        // 1. Validasi dasar untuk semua role
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,kurir',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
            'status'   => 'required|in:Aktif,Cuti,Berhenti',
        ];

        // 2. Validasi tambahan khusus role kurir
        if ($request->role === 'kurir') {
            $rules['nik']        = 'required|numeric|unique:users,nik';
            $rules['sim_number'] = 'required|numeric';
            $rules['sim_type']   = 'required|in:SIM A,SIM B1,SIM B1 Umum,SIM B2,SIM B2 Umum';
        }

        $validated = $request->validate($rules);

        // 3. Enkripsi kata sandi sebelum disimpan
        $validated['password'] = Hash::make($validated['password']);

        // 4. Generate kode kurir otomatis jika role adalah kurir
        if ($request->role === 'kurir') {
            $lastCourier = User::where('role', 'kurir')->whereNotNull('courier_code')->orderBy('id', 'desc')->first();
            $newNumber   = $lastCourier ? (int) substr($lastCourier->courier_code, 3) + 1 : 1;
            $validated['courier_code'] = 'KRR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        }

        // 5. Simpan akun baru ke database
        User::create($validated);

        $label = $request->role === 'admin' ? 'Admin' : 'Kurir';
        return redirect()->back()->with('success', "Akun {$label} berhasil ditambahkan!");
    }

    public function update(Request $request, User $user)
    {
        // 1. Validasi dasar untuk semua role
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
            'status'   => 'required|in:Aktif,Cuti,Berhenti',
        ];

        // 2. Validasi tambahan jika role kurir
        if ($user->role === 'kurir') {
            $rules['nik']        = ['required', 'numeric', Rule::unique('users')->ignore($user->id)];
            $rules['sim_number'] = 'required|numeric';
            $rules['sim_type']   = 'required|in:SIM A,SIM B1,SIM B1 Umum,SIM B2,SIM B2 Umum';
        }

        $validated = $request->validate($rules);

        // 3. Hanya enkripsi ulang password jika diisi
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // 4. Simpan perubahan ke database
        $user->update($validated);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // 1. Cegah admin menghapus akun dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors('Tidak bisa menghapus akun Anda sendiri!');
        }

        // 2. Hapus akun dari sistem (soft delete)
        $user->delete();
        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus!');
    }
}
