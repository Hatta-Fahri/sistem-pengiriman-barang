<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CourierController extends Controller
{
    // 1. Tampilkan daftar kurir (read-only) — tidak ada aksi tambah/edit/hapus di halaman ini
    public function index()
    {
        $couriers = User::where('role', 'kurir')->latest()->paginate(15);
        return view('admin.couriers.index', compact('couriers'));
    }
}
