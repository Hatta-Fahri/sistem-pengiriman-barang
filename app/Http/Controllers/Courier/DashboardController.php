<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Manifest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $courier = Auth::user();

        $activeManifest = Manifest::with(['vehicle', 'shipments'])
            ->where('courier_id', $courier->id)
            ->where('status', 'Sedang Jalan')
            ->first();

        return view('courier.dashboard', compact('courier', 'activeManifest'));
    }
}
