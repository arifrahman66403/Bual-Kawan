<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\TrackingController;
use App\Http\Controllers\KisUserController;
use App\Http\Controllers\KisQrCodeController;
use App\Http\Controllers\KisTrackingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\KisPengunjungController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;


// Route Beranda (Area publik)
Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');
Route::view('/statistik', 'statistik')->name('statistik');
Route::view('/tentang/profil', 'tentang.profil')->name('tentang.profil');
Route::view('/tentang/visi-misi', 'tentang.visi-misi')->name('tentang.visi-misi');
Route::view('/berita', 'berita.berita')->name('berita.berita');
Route::view('/berita-detail', 'berita.berita-detail')->name('berita-detail');   

Route::get('/kunjungan', [KisPengunjungController::class, 'index'])->name('kunjungan.index');
Route::get('/kunjungan/create', [KisPengunjungController::class, 'showCreateForm'])->name('kunjungan.create');
Route::post('/kunjungan', [KisPengunjungController::class, 'storeKunjungan'])->name('kunjungan.store');
Route::get('/kunjungan/detail/{id}', [KisPengunjungController::class, 'showDetail'])->name('kunjungan.detail');
Route::post('/kunjungan/upload-spt/{uid}', [KisPengunjungController::class, 'uploadSpt'])->name('kunjungan.upload.spt');
Route::get('pengunjung/scan/{uid}', [KisQrCodeController::class, 'showParticipantForm'])->name('pengunjung.scan');
Route::post('pengunjung/store-peserta/{uid}', [KisQrCodeController::class, 'storeParticipantData'])->name('pengunjung.store.peserta');

// Route Login Admin (Area publik)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); // Gunakan nama route 'logout'

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Profil Admin
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::put('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::put('/admin/profile/password', [AdminController::class, 'updatePassword'])->name('admin.profile.password');

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Menampilkan daftar galeri
    Route::get('/gallery', [GalleryController::class, 'index'])->name('admin.gallery.index');
    Route::get('/gallery/create', [GalleryController::class, 'create'])->name('admin.gallery.create');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('admin.gallery.store');
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');

    // Routes Slider
    Route::get('/slider', [SliderController::class, 'index'])->name('admin.slider.index');
    Route::get('/slider/create', [SliderController::class, 'create'])->name('admin.slider.create');
    Route::post('/slider', [SliderController::class, 'store'])->name('admin.slider.store');
    Route::delete('/slider/{id}', [SliderController::class, 'destroy'])->name('admin.slider.destroy');

    // === QR CODE ===
    Route::get('/qr', [KisQrCodeController::class, 'index'])->name('qr.index');
    Route::get('/qr/create', [KisQrCodeController::class, 'create'])->name('qr.create');
    Route::post('/qr', [KisQrCodeController::class, 'store'])->name('qr.store');
    Route::get('/admin/pengajuan', [PengajuanController::class, 'index'])->name('admin.pengajuan');
    Route::post('/pengajuan/{uid}/status', [PengajuanController::class, 'updateStatus'])->name('admin.pengajuan.status');
    Route::get('/pengajuan/{uid}', [PengajuanController::class, 'show'])->name('admin.pengajuan.show');
    Route::get('/admin/pengajuan/export', [PengajuanController::class, 'exportPengunjung'])->name('admin.pengajuan.export');
    Route::get('/admin/riwayat', [RiwayatController::class, 'index'])->name('admin.riwayat');
    Route::get('/admin/riwayat/export', [RiwayatController::class, 'exportTracking'])->name('admin.riwayat.export');

    // === TRACKING ===
    Route::get('/tracking', [KisTrackingController::class, 'index'])->name('tracking.index');
    Route::post('/tracking/scan', [KisTrackingController::class, 'scan'])->name('tracking.scan');

    // === LOG ADMIN ===
    Route::get('/users', [KisUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [KisUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [KisUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [KisUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [KisUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [KisUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/users/{id}/toggle', [KisUserController::class, 'toggleStatus'])->name('admin.users.toggle');
});
