<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Controllers\Admin\ShipmentController; // Import Controller Resi
use App\Http\Controllers\Admin\ManifestController; // Import Controller Jadwal
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

        // Rute & Tarif
        Route::get('/shipping-rates', [ShippingRateController::class, 'index']);
        Route::post('/shipping-rates', [ShippingRateController::class, 'store']);
        Route::put('/shipping-rates/{id}', [ShippingRateController::class, 'update']);
        Route::delete('/shipping-rates/{id}', [ShippingRateController::class, 'destroy']);

        // Pengiriman (Resi) - Menggunakan Resource agar men-cover Create, Store, Index, dll
        Route::resource('shipments', ShipmentController::class);

        // Operasional Penjadwalan (Manifest)
        Route::resource('manifests', ManifestController::class);
    });

    // ==========================================================
    // MODULE: COURIER SYSTEM (Kurir Lapangan)
    // ==========================================================
    Route::prefix('courier')->group(function () {

        // Dashboard Kurir
        Route::get('/dashboard', [CourierDashboardController::class, 'index'])->name('courier.dashboard');
    });
});
