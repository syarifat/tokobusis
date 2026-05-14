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
        'metode_pengiriman', // <-- TAMBAHKAN INI
        'tipe_pembayaran',   // <-- TAMBAHKAN INI
        'jumlah_cicilan',
        'total_dibayar',
        'status_pesanan',
        'status_pembayaran',
        'catatan',
        'longitude',
        'latitude',
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

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // Hitung berapa bulan keterlambatan
    public function getMonthsOverdue()
    {
        if ($this->status_pembayaran === 'lunas' || $this->jenis_pesanan !== 'event' || !$this->tenggat_pembayaran) {
            return 0;
        }

        $tenggat = \Carbon\Carbon::parse($this->tenggat_pembayaran)->startOfDay();
        $sekarang = now()->startOfDay();

        $daysOverdue = $tenggat->diffInDays($sekarang, false);

        if ($daysOverdue <= 0) {
            return 0;
        }

        return ceil($daysOverdue / 30);
    }

    // Hitung nominal denda (10% per bulan dari sisa tagihan)
    public function hitungDenda()
    {
        $monthsOverdue = $this->getMonthsOverdue();
        if ($monthsOverdue <= 0) {
            return 0;
        }

        $sisa = $this->total_harga - $this->total_dibayar;
        if ($sisa <= 0) {
            return 0;
        }

        return $sisa * 0.10 * $monthsOverdue;
    }
}