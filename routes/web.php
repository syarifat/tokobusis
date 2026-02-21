<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// ADMIN
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\EventController as AdminEventController;

//PELANGGAN
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboard;
use App\Http\Controllers\Pelanggan\KeranjangController;
use App\Http\Controllers\Pelanggan\EventController as PelangganEventController;

Route::get('/', function () {
    return view('welcome');
});

// Route khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);
    Route::get('/event', [AdminEventController::class, 'index'])->name('event.index');
    Route::put('/event/{event}/status', [AdminEventController::class, 'updateStatus'])->name('event.updateStatus');
});

// Route khusus Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganDashboard::class, 'index'])->name('dashboard');
    // Keranjang Belanja
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::put('/keranjang/update/{keranjang}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/hapus/{keranjang}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
    Route::get('/event', [PelangganEventController::class, 'index'])->name('event.index');
    Route::post('/event/ajukan', [PelangganEventController::class, 'store'])->name('event.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
