<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Courier\DashboardController as CourierDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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

        // Nanti route master data dan transaksi admin ditaruh di sini
        // Route::get('/shipments', [ShipmentController::class, 'index']);
    });

    // ==========================================================
    // MODULE: COURIER SYSTEM (Kurir Lapangan)
    // ==========================================================
    Route::prefix('courier')->group(function () {

        // Dashboard Kurir
        Route::get('/dashboard', [CourierDashboardController::class, 'index'])->name('courier.dashboard');

        // Nanti route update status kurir ditaruh di sini
        // Route::post('/trackings', [ShipmentTrackingController::class, 'store']);
    });

});
