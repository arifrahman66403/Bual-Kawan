<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\KisQrCodeController;
use App\Http\Controllers\KisTrackingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;


// Route Login Admin (Area publik)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Gunakan nama route 'logout'

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [SuperAdminController::class, 'manageUsers'])->name('users.index');
    // Tambahkan route CRUD untuk user di sini
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Manajemen Pengajuan dan Data Master Pengunjung
    Route::get('/pengajuan', [AdminController::class, 'pendingList'])->name('pengajuan.index');
    Route::post('/pengajuan/approve/{id}', [AdminController::class, 'approvePengunjung'])->name('pengajuan.approve');
    
    // Data Master Pengunjung
    Route::get('/pengunjung', [AdminController::class, 'allPengunjung'])->name('pengunjung.index');
    // Route CRUD lainnya
});

Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', [OperatorController::class, 'index'])->name('dashboard');
    Route::get('/pengunjung', [PengunjungController::class, 'create'])->name('pengunjung.create');
    Route::post('/pengunjung', [PengunjungController::class, 'store'])->name('pengunjung.store');
    
    // Fungsi Scan QR
    Route::post('/scan', [OperatorController::class, 'processScan'])->name('scan');
    
    // Riwayat
    Route::get('/riwayat', [OperatorController::class, 'riwayatScan'])->name('riwayat');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // === QR CODE ===
    Route::get('/qr', [KisQrCodeController::class, 'index'])->name('qr.index');
    Route::get('/qr/create', [KisQrCodeController::class, 'create'])->name('qr.create');
    Route::post('/qr', [KisQrCodeController::class, 'store'])->name('qr.store');
    Route::get('/pengunjung', [PengunjungController::class, 'index'])->name('admin.pengunjung.index');
    Route::get('/pengunjung/{id}', [PengunjungController::class, 'show'])->name('pengunjung.show');
    Route::post('/pengunjung/{id}/status', [TrackingController::class, 'update'])->name('admin.pengunjung.status');
    Route::get('/verifikasi', [PengunjungController::class, 'verifyList'])->name('admin.verify');
    Route::get('/tracking', [TrackingController::class, 'index'])->name('admin.index');

    // === TRACKING ===
    Route::get('/tracking', [KisTrackingController::class, 'index'])->name('tracking.index');
    Route::post('/tracking/scan', [KisTrackingController::class, 'scan'])->name('tracking.scan');
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