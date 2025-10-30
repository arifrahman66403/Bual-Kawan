<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\TrackingController;

Route::get('/', function () {
    return view('login_admin.login_admin');
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