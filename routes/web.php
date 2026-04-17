<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\ManifestController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\CourierController;

// Courier Controllers
use App\Http\Controllers\Courier\DashboardController as CourierDashboardController;
use App\Http\Controllers\Courier\ShipmentController as CourierShipmentController;
use App\Http\Controllers\Courier\ManifestController as CourierManifestController;


// ==========================================================
// 0. ROOT ROUTE
// ==========================================================
// Mengarahkan langsung pengguna baru ke halaman login
Route::get('/', [AuthController::class, 'index']);


// ==========================================================
// 1. PUBLIC ROUTES (Guest / Belum Login)
// ==========================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});


// ==========================================================
// 2. PROTECTED ROUTES (Wajib Login / Session Auth)
// ==========================================================
Route::middleware('auth')->group(function () {

    // --- AUTH ACTIONS ---
    // Rute untuk keluar dari aplikasi (Logout)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    // ==========================================================
    // MODULE: ADMIN SYSTEM
    // Akses khusus manajemen gudang dan operasional utama
    // ==========================================================
    Route::prefix('admin')->group(function () {

        // --- DASHBOARD ADMIN ---
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // --- MASTER DATA ---
        // 1. Kelola Rute & Tarif Pengiriman
        Route::resource('shipping-rates', ShippingRateController::class)->except(['create', 'edit', 'show']);

        // 2. Kelola Armada Kendaraan (Truk/Mobil)
        Route::resource('vehicles', VehicleController::class)->except(['create', 'edit']);

        // 3. Kelola Akun Kurir Lapangan
        Route::resource('couriers', CourierController::class)->except(['create', 'edit', 'show']);

        // --- OPERASIONAL ---
        // 1. Kelola Data Resi / Paket Customer
        Route::resource('shipments', ShipmentController::class);

        // 2. Kelola Penjadwalan (Manifest)
        // Rute custom harus diletakkan sebelum Route::resource agar tidak terbaca sebagai parameter ID
        Route::post('manifests/{manifest}/berangkatkan', [ManifestController::class, 'berangkatkan'])->name('manifests.berangkatkan');
        Route::post('manifests/{manifest}/generate', [ManifestController::class, 'generate'])->name('manifests.generate');
        Route::resource('manifests', ManifestController::class);
    });


    // ==========================================================
    // MODULE: COURIER SYSTEM (Kurir Lapangan)
    // Akses khusus untuk eksekusi pengantaran paket di lapangan
    // ==========================================================
    Route::prefix('courier')->group(function () {

        // --- DASHBOARD KURIR ---
        // Menampilkan ringkasan tugas aktif kurir hari ini
        Route::get('/dashboard', [CourierDashboardController::class, 'index'])->name('courier.dashboard');

        // --- OPERASIONAL LAPANGAN ---
        // 1. Lihat daftar paket di dalam truk & Update status pengantaran (Diterima/Gagal)
        Route::get('/shipments', [CourierShipmentController::class, 'index'])->name('courier.shipments');
        Route::put('/shipments/{shipment}/status', [CourierShipmentController::class, 'updateStatus'])->name('courier.shipments.update-status');

        // 2. Selesaikan Tugas (Menutup manifest dan mengembalikan status truk)
        Route::post('/manifests/{manifest}/complete', [CourierManifestController::class, 'complete'])->name('courier.manifests.complete');

    });
});
