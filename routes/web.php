<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\QrCodeController;

Route::get('/', [LoginController::class, 'showLogin']);
Route::post('/login/check', [LoginController::class, 'checkLogin']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware(['checklogin'])->prefix('admin')->group(function() {

    Route::get('/dashboard', [DashboardController::class, 'dashboard']);

    // Short links
    Route::get('/shortlinks', [ShortLinkController::class, 'showPage']);
    Route::post('/shorten', [ShortLinkController::class, 'store']);
    Route::post('/shorten/{id}/update', [ShortLinkController::class, 'update']);
    Route::delete('/shorten/{id}', [ShortLinkController::class, 'destroy']);
    Route::get('/shorten/{id}/get', [ShortLinkController::class, 'getLink']);
    Route::get('/l/{code}', [ShortLinkController::class, 'redirect']);

    // QR Code
    Route::get('/qr', [QrCodeController::class, 'showPage']);
    Route::post('/qr/create', [QrCodeController::class, 'store']);
    Route::get('/qr/{id}/get', [QrCodeController::class, 'getQr']);
    Route::post('/qr/{id}/update', [QrCodeController::class, 'update']);
    Route::delete('/qr/{id}', [QrCodeController::class, 'destroy']);

    
});
