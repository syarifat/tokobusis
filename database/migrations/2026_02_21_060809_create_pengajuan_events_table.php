<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_acara'); // Misal: "Pernikahan", "Pitonan Anak"
            $table->date('tanggal_acara');
            $table->text('keterangan')->nullable(); // Alasan/detail tambahan
            
            // Status pengajuan
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'selesai'])->default('menunggu');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_events');
    }
};