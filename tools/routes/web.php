<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\LoginController;

// Login routes
Route::get('/', [LoginController::class, 'showLogin']); // login page
Route::post('/login/check', [LoginController::class, 'checkLogin']);
Route::get('/logout', [LoginController::class, 'logout']);

// Protected admin routes
Route::middleware(['checklogin'])->prefix('admin')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    Route::get('/', [DashboardController::class, 'index']); // optional
    Route::get('/shortlinks', [ShortLinkController::class, 'showPage']);
    Route::post('/shorten', [ShortLinkController::class, 'store']);
    Route::post('/shorten/{id}/update', [ShortLinkController::class, 'update']);
    Route::delete('/shorten/{id}', [ShortLinkController::class, 'destroy']);
    Route::get('/shorten/{id}/get', [ShortLinkController::class, 'getLink']);
    Route::get('/l/{code}', [ShortLinkController::class, 'redirect']);
    Route::get('/go/{code}', [ShortLinkController::class, 'processRedirect']);
});
