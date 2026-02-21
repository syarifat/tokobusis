<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'nama_barang',
        'deskripsi',
        'harga',
        'stok',
        'satuan',
        'gambar',
    ];

    // Relasi: Barang milik satu kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi: Barang bisa ada di banyak detail pesanan
    public function pesanan_items()
    {
        return $this->hasMany(PesananItem::class);
    }
}