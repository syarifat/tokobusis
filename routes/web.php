<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// ADMIN
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PesananController as AdminPesanan;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LaporanController;

//PELANGGAN
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboard;
use App\Http\Controllers\Pelanggan\KeranjangController;
use App\Http\Controllers\Pelanggan\EventController as PelangganEventController;
use App\Http\Controllers\Pelanggan\CheckoutController;
use App\Http\Controllers\Pelanggan\PesananController as PelangganPesanan;
use App\Http\Controllers\Pelanggan\PembayaranController;
use App\Services\FonnteService;

Route::get('/', function () {
    $topItems = \App\Models\Barang::withSum('pesanan_items', 'qty')
        ->orderByDesc('pesanan_items_sum_qty')
        ->take(3)
        ->get();
        
    return view('welcome', compact('topItems'));
});

// Route khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);
    Route::get('/event', [AdminEventController::class, 'index'])->name('event.index');
    Route::put('/event/{event}/status', [AdminEventController::class, 'updateStatus'])->name('event.updateStatus');
    Route::get('/pesanan', [AdminPesanan::class, 'index'])->name('pesanan.index');
    Route::put('/pesanan/{pesanan}/status', [AdminPesanan::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::get('/pesanan/{pesanan}', [AdminPesanan::class, 'show'])->name('pesanan.show');
    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
    Route::post('/stok', [StokController::class, 'store'])->name('stok.store');
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::post('/pesanan/{id}/bayar-tunai', [AdminPesanan::class, 'bayarTunai'])->name('pesanan.bayarTunai');
});

// Route khusus Pelanggan
Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganDashboard::class, 'index'])->name('dashboard');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::post('/keranjang/bulk-store', [KeranjangController::class, 'bulkStore'])->name('keranjang.bulkStore');
    
    Route::middleware(['auth', 'role:pelanggan'])->group(function () {
        // Keranjang Belanja
        Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
        Route::put('/keranjang/update/{keranjang}', [KeranjangController::class, 'update'])->name('keranjang.update');
        Route::delete('/keranjang/hapus/{keranjang}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
        Route::get('/event', [PelangganEventController::class, 'index'])->name('event.index');
    Route::post('/event/ajukan', [PelangganEventController::class, 'store'])->name('event.store');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/proses', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/pesanan', [PelangganPesanan::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [PelangganPesanan::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{pesanan}/pay', [PembayaranController::class, 'pay'])->name('pembayaran.pay');
    Route::get('/rekomendasi', [\App\Http\Controllers\Pelanggan\RekomendasiController::class, 'index'])->name('rekomendasi.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/midtrans/webhook', [App\Http\Controllers\MidtransWebhookController::class, 'handler']);

require __DIR__.'/auth.php';
