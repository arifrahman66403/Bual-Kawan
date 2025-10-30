<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\KisQrCodeController;
use App\Http\Controllers\KisTrackingController;
use App\Http\Controllers\Admin\LoginAdminController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
// Login Admin
Route::get('/', [LoginAdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginAdminController::class, 'login'])->name('admin.login.submit');

// ==== SUPERADMIN (akses semua) ====
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/super/dashboard', function () {
        return view('super.dashboard');
    })->name('super.dashboard');
});

// ==== ADMIN (bisa buat QR code) ====
Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {
    Route::get('/admin/qr', [KisQrCodeController::class, 'index'])->name('admin.qr.index');
    Route::post('/admin/qr', [KisQrCodeController::class, 'store'])->name('admin.qr.store');
    Route::delete('/admin/qr/{id}', [KisQrCodeController::class, 'destroy'])->name('admin.qr.destroy');
});

// ==== OPERATOR (scan & tracking) ====
Route::middleware(['auth', 'role:operator,superadmin'])->group(function () {
    Route::get('/operator/tracking', [KisTrackingController::class, 'index'])->name('operator.tracking.index');
    Route::post('/operator/scan', [KisTrackingController::class, 'scan'])->name('operator.tracking.scan');
    Route::put('/operator/tracking/{id}/update', [KisTrackingController::class, 'update'])->name('operator.tracking.update');
});

Route::prefix('admin')->group(function () {
    Route::get('/qr', [QrCodeController::class, 'index']);
    Route::post('/qr', [QrCodeController::class, 'store']);
    Route::get('/qr/{id}', [QrCodeController::class, 'show']);
    Route::put('/qr/{id}', [QrCodeController::class, 'update']);
    Route::delete('/qr/{id}', [QrCodeController::class, 'destroy']);

    Route::get('/tracking', [TrackingController::class, 'index']);
    Route::post('/tracking', [TrackingController::class, 'store']);
    Route::put('/tracking/{id}', [TrackingController::class, 'update']);
    Route::delete('/tracking/{id}', [TrackingController::class, 'destroy']);
});