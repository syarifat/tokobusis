<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_pesanan')->unique(); // Format: ORD-YYYYMMDD-XXX
            
            // Kolom penentu jenis transaksi
            $table->enum('jenis_pesanan', ['reguler', 'event'])->default('reguler');
            $table->string('nama_event')->nullable(); // Contoh: "Pernikahan Budi"
            $table->date('tanggal_acara')->nullable();
            $table->date('tanggal_pengantaran')->nullable();
            $table->date('tenggat_pembayaran')->nullable(); // Batas cicilan/pelunasan
            
            // Nominal
            $table->integer('total_harga');
            $table->integer('total_dibayar')->default(0); // Akumulasi uang masuk (cicilan)
            
            // Status
            $table->enum('status_pesanan', ['menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->enum('status_pembayaran', ['belum_bayar', 'cicilan', 'lunas'])->default('belum_bayar');
            
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};