<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nama_acara', 'tanggal_acara', 'keterangan', 'status'
    ];

    protected $casts = [
        'tanggal_acara' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}