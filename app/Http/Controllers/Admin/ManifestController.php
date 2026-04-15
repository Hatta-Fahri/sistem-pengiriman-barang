<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManifestController extends Controller
{
    public function index()
    {
        return view('admin.manifests.index');
    }

    // Fungsi-fungsi lain (create, store, show) akan kita tambahkan
    // setelah form pembuatan resi selesai dibuat.
}
