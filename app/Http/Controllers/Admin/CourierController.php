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
        $couriers = User::where('role', 'kurir')->latest()->paginate(10);
        return view('admin.couriers.index', compact('couriers'));
    }

    public function store(Request $request)
    {
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

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'kurir';

        $lastCourier = User::where('role', 'kurir')->whereNotNull('courier_code')->orderBy('id', 'desc')->first();
        if ($lastCourier) {

            $lastNumber = (int) substr($lastCourier->courier_code, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        // Format kembali menjadi KRR + 3 digit angka
        $validated['courier_code'] = 'KRR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        User::create($validated);

        return redirect()->back()->with('success', 'Akun kurir berhasil ditambahkan!');
    }

    public function update(Request $request, User $courier)
    {
        if ($courier->role !== 'kurir') abort(403, 'Aksi tidak diizinkan.');

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

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $courier->update($validated);

        return redirect()->back()->with('success', 'Data kurir berhasil diperbarui!');
    }

    public function destroy(User $courier)
    {
        if ($courier->role !== 'kurir') abort(403, 'Aksi tidak diizinkan.');

        $courier->delete();
        return redirect()->back()->with('success', 'Akun kurir berhasil dinonaktifkan!');
    }
}
