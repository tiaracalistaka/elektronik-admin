<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

// Login routes (no auth needed)
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::post('/produk', [ProdukController::class, 'store']);
    Route::put('/produk/{produk}', [ProdukController::class, 'update']);
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy']);
});

// Root redirect
Route::get('/', function () {
    return redirect('/dashboard');
});
