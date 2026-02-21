<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            
            // Ini akan menjadi `order_id` yang dikirim ke Midtrans. 
            // Setiap cicilan harus punya ID unik, misal: PAY-ORD-001-P1
            $table->string('kode_pembayaran')->unique(); 
            
            $table->integer('nominal_bayar');
            $table->string('metode_pembayaran')->nullable(); // Diisi dari response Midtrans (cth: qris, bca_va)
            $table->string('snap_token')->nullable(); // Disimpan untuk me-load pop-up Midtrans
            
            $table->enum('status_transaksi', ['pending', 'sukses', 'gagal', 'kadaluarsa'])->default('pending');
            $table->dateTime('waktu_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};