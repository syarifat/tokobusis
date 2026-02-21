<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// ADMIN
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BarangController;

//PELANGGAN
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboard;

Route::get('/', function () {
    return view('welcome');
});

// Route khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);
});

// Route khusus Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganDashboard::class, 'index'])->name('dashboard');
    // Nanti tambahkan route keranjang, checkout, riwayat pesanan di sini
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
