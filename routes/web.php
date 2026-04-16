<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\ManifestController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Courier\DashboardController as CourierDashboardController;

// Redirect root ke login
Route::get('/', [AuthController::class, 'index']);

// ==========================================================
// 1. PUBLIC ROUTES (Belum Login / Guest)
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
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==========================================================
    // MODULE: ADMIN SYSTEM
    // ==========================================================
    Route::prefix('admin')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Master Data: Rute & Tarif
        Route::resource('shipping-rates', ShippingRateController::class)->except(['create', 'edit', 'show']);

        // Master Data: Armada Kendaraan
        Route::resource('vehicles', VehicleController::class)->except(['create', 'edit']);

        // Master Data: Manajemen Kurir
        Route::resource('couriers', CourierController::class)->except(['create', 'edit', 'show']);

        // Operasional: Pengiriman (Resi)
        Route::resource('shipments', ShipmentController::class);

        Route::post('manifests/{manifest}/generate', [ManifestController::class, 'generate'])->name('manifests.generate');
        Route::resource('manifests', ManifestController::class)->except(['create', 'edit']);
    });

    // ==========================================================
    // MODULE: COURIER SYSTEM (Kurir Lapangan)
    // ==========================================================
    Route::prefix('courier')->group(function () {

        // Dashboard Kurir
        Route::get('/dashboard', [CourierDashboardController::class, 'index'])->name('courier.dashboard');

    });
});
