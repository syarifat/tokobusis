<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'kode_pembayaran',
        'nominal_bayar',
        'metode_pembayaran',
        'snap_token',
        'status_transaksi',
        'waktu_bayar',
    ];

    protected $casts = [
        'waktu_bayar' => 'datetime',
    ];

    // Relasi: Pembayaran/Cicilan ini untuk pesanan yang mana
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}