<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'barang_id',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    // Relasi: Item ini milik pesanan siapa
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    // Relasi: Item ini barangnya apa
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}