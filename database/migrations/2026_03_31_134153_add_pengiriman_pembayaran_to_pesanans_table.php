<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->enum('metode_pengiriman', ['diantar', 'ambil_sendiri'])->default('diantar')->after('ongkir');
            $table->enum('tipe_pembayaran', ['transfer', 'cash'])->default('transfer')->after('metode_pengiriman');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['metode_pengiriman', 'tipe_pembayaran']);
        });
    }
};