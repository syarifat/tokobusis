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

    // Hitung nominal denda (2% per cicilan yang nunggak/terlewat)
    public function hitungDenda()
    {
        if ($this->status_pembayaran === 'lunas' || $this->jenis_pesanan !== 'event' || !$this->tenggat_pembayaran || !$this->jumlah_cicilan) {
            return 0;
        }

        $sekarang = now()->startOfDay();
        $start = \Carbon\Carbon::parse($this->tanggal_acara)->startOfDay();
        $end = \Carbon\Carbon::parse($this->tenggat_pembayaran)->startOfDay();

        // Jika belum masuk masa cicilan (sebelum acara), tidak ada denda
        if ($sekarang->lte($start)) {
            return 0;
        }

        $totalDays = max(1, $start->diffInDays($end));
        $daysPassed = $start->diffInDays($sekarang);
        
        // Interval hari per cicilan
        $intervalDays = $totalDays / max(1, $this->jumlah_cicilan);

        // Berapa cicilan yang SUDAH JATUH TEMPO sampai hari ini
        $installmentsDue = floor($daysPassed / $intervalDays);
        if ($installmentsDue > $this->jumlah_cicilan) {
            $installmentsDue = $this->jumlah_cicilan;
        }

        if ($installmentsDue <= 0) {
            return 0;
        }

        // Nominal pokok per cicilan
        $nominalPerCicilan = $this->total_harga / $this->jumlah_cicilan;
        
        // Ekspektasi total uang yang harusnya sudah masuk hari ini
        $expectedPayment = $installmentsDue * $nominalPerCicilan;
        
        // Kurangi dengan yang sudah dibayar untuk melihat apakah ada tunggakan
        $shortfall = $expectedPayment - $this->total_dibayar;

        if ($shortfall > 0) {
            // Hitung berapa jumlah cicilan yang nunggak
            $missedInstallments = ceil($shortfall / $nominalPerCicilan);
            
            // Denda 2% dari nominal cicilan untuk setiap cicilan yang nunggak
            return round($missedInstallments * ($nominalPerCicilan * 0.02));
        }

        return 0;
    }

    public function getMissedInstallments()
    {
        if ($this->status_pembayaran === 'lunas' || $this->jenis_pesanan !== 'event' || !$this->tenggat_pembayaran || !$this->jumlah_cicilan) {
            return 0;
        }

        $sekarang = now()->startOfDay();
        $start = \Carbon\Carbon::parse($this->tanggal_acara)->startOfDay();
        $end = \Carbon\Carbon::parse($this->tenggat_pembayaran)->startOfDay();

        if ($sekarang->lte($start)) {
            return 0;
        }

        $totalDays = max(1, $start->diffInDays($end));
        $daysPassed = $start->diffInDays($sekarang);
        
        $intervalDays = $totalDays / max(1, $this->jumlah_cicilan);

        $installmentsDue = floor($daysPassed / $intervalDays);
        if ($installmentsDue > $this->jumlah_cicilan) {
            $installmentsDue = $this->jumlah_cicilan;
        }

        if ($installmentsDue <= 0) {
            return 0;
        }

        $nominalPerCicilan = $this->total_harga / $this->jumlah_cicilan;
        $expectedPayment = $installmentsDue * $nominalPerCicilan;
        $shortfall = $expectedPayment - $this->total_dibayar;

        if ($shortfall > 0) {
            return ceil($shortfall / $nominalPerCicilan);
        }

        return 0;
    }

    public function getSisaTagihanAttribute()
    {
        // Jika sudah lunas di DB, sisa pasti 0
        if ($this->status_pembayaran === 'lunas') {
            return 0;
        }

        // Denda aktif adalah denda yang belum dibayar
        // Jika total_dibayar > total_harga, berarti denda sudah mulai dicicil
        $denda = $this->hitungDenda();
        
        $totalTagihan = $this->total_harga + $denda;
        return max(0, $totalTagihan - $this->total_dibayar);
    }

    public function getIsLunasAttribute()
    {
        if ($this->status_pembayaran === 'lunas') {
            return true;
        }
        return $this->sisa_tagihan <= 0;
    }

    public function syncStatusPembayaran()
    {
        $status = $this->is_lunas ? 'lunas' : ($this->total_dibayar > 0 ? 'cicilan' : 'belum_bayar');
        $this->update(['status_pembayaran' => $status]);
        return $status;
    }
}