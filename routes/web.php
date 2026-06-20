<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\ManifestController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;

// Courier Controllers
use App\Http\Controllers\Courier\DashboardController as CourierDashboardController;
use App\Http\Controllers\Courier\ShipmentController as CourierShipmentController;
use App\Http\Controllers\Courier\ManifestController as CourierManifestController;
use App\Http\Controllers\Courier\HistoryController;

// Public Controllers
use App\Http\Controllers\TrackingController;

// ==========================================================
// 1. PUBLIC ROUTES (Landing Page & Tracking Customer)
// ==========================================================
// Mengarahkan pengguna langsung ke halaman tracking (welcome)
Route::get('/', [TrackingController::class, 'index'])->name('tracking.index');


// ==========================================================
// 2. GUEST ROUTES (Belum Login)
// ==========================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});


// ==========================================================
// 3. PROTECTED ROUTES (Wajib Login / Session Auth)
// ==========================================================
Route::middleware('auth')->group(function () {

    // --- AUTH ACTIONS ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // MODULE: ADMIN SYSTEM
    Route::prefix('admin')->middleware('role:admin')->group(function () {

        // DASHBOARD ADMIN
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // MASTER DATA
        Route::resource('shipping-rates', ShippingRateController::class)->except(['create', 'edit', 'show']);

        // Rute AJAX untuk Select2 & Kalkulator Ongkir
        Route::get('/ajax/destinations', [ShipmentController::class, 'ajaxDestinations'])->name('ajax.destinations');
        Route::get('/ajax/rate', [ShipmentController::class, 'ajaxRate'])->name('ajax.rate');

        Route::resource('vehicles', VehicleController::class)->except(['create', 'edit']);
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']); // Manajemen Pengguna

        // OPERASIONAL
        Route::resource('shipments', ShipmentController::class);

        Route::post('manifests/{manifest}/berangkatkan', [ManifestController::class, 'berangkatkan'])->name('manifests.berangkatkan');
        Route::post('manifests/{manifest}/batalkan-tugas', [ManifestController::class, 'batalkanTugas'])->name('manifests.batalkanTugas');
        Route::post('manifests/{manifest}/generate', [ManifestController::class, 'generate'])->name('manifests.generate');
        Route::resource('manifests', ManifestController::class);

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    });

    // MODULE: COURIER SYSTEM
    Route::prefix('courier')->middleware('role:kurir')->group(function () {

        // DASHBOARD KURIR
        Route::get('/dashboard', [CourierDashboardController::class, 'index'])->name('courier.dashboard');

        // OPERASIONAL LAPANGAN
        Route::get('/shipments', [CourierShipmentController::class, 'index'])->name('courier.shipments');
        Route::put('/shipments/{shipment}/status', [CourierShipmentController::class, 'updateStatus'])->name('courier.shipments.update-status');

        Route::post('/manifests/{manifest}/start', [CourierDashboardController::class, 'startJourney'])->name('courier.manifests.start');
        Route::post('/manifests/{manifest}/complete', [CourierManifestController::class, 'complete'])->name('courier.manifests.complete');

        Route::get('/history', [HistoryController::class, 'index'])->name('courier.history.index');
    });
});
