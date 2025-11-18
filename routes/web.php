<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\KisQrCodeController;
use App\Http\Controllers\KisTrackingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\KisPengunjungController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;


// Route Beranda (Area publik)
Route::view('/', 'beranda')->name('beranda');
Route::view('/statistik', 'statistik')->name('statistik');
Route::view('/tentang/profil', 'tentang.profil')->name('tentang.profil');
Route::view('/tentang/visi-misi', 'tentang.visi-misi')->name('tentang.visi-misi');

Route::get('/kunjungan', [GuestController::class, 'index'])->name('kunjungan.index');
// Form Pengajuan Kunjungan (Tambah Kunjungan)
Route::get('/kunjungan/create', [GuestController::class, 'showCreateForm'])->name('kunjungan.create');
Route::post('/kunjungan', [GuestController::class, 'storeKunjungan'])->name('kunjungan.store');
Route::get('/kunjungan/detail/{id}', [GuestController::class, 'showDetail'])->name('kunjungan.detail');
Route::get('/kunjungan/{id}/tambah-peserta', [GuestController::class, 'showAddPesertaForm'])->name('peserta.create');
Route::post('/kunjungan/{id}/peserta', [GuestController::class, 'storePeserta'])->name('peserta.store');

// Route Login Admin (Area publik)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); // Gunakan nama route 'logout'

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [SuperAdminController::class, 'manageUsers'])->name('users.index');
    Route::get('/log', [LogController::class, 'index'])->name('log.index');
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



Route::middleware(['auth', 'role:operator,admin'])->prefix('/operator')->name('operator.')->group(function () {
    Route::get('/dashboard', [OperatorController::class, 'index'])->name('dashboard');
    Route::get('/pengunjung', [KisPengunjungController::class, 'create'])->name('pengunjung.create');
    Route::post('/pengunjung', [KisPengunjungController::class, 'store'])->name('pengunjung.store');
    
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
    Route::get('/admin/pengajuan', [PengajuanController::class, 'index'])->name('admin.pengajuan');
    Route::post('/pengajuan/{uid}/status', [PengajuanController::class, 'updateStatus'])->name('admin.pengajuan.status');
    Route::get('/pengajuan/{uid}', [PengajuanController::class, 'show'])->name('admin.pengajuan.show');
    Route::get('/admin/riwayat', [RiwayatController::class, 'index'])->name('admin.riwayat');
    Route::get('/admin/riwayat/export', [RiwayatController::class, 'exportTracking'])->name('admin.riwayat.export');

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