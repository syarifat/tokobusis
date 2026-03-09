<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_pesanan',
        'jenis_pesanan',
        'nama_event',
        'tanggal_acara',
        'tanggal_pengantaran',
        'tenggat_pembayaran',
        'total_harga',
        'ongkir',
        'total_dibayar',
        'status_pesanan',
        'status_pembayaran',
        'catatan',
    ];

    protected $casts = [
        'tanggal_acara' => 'date',
        'tanggal_pengantaran' => 'date',
        'tenggat_pembayaran' => 'date',
    ];

    // Relasi: Pesanan milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu pesanan punya banyak item/barang
    public function items()
    {
        return $this->hasMany(PesananItem::class);
    }

    // Relasi: Satu pesanan bisa dicicil berkali-kali (punya banyak riwayat pembayaran)
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}