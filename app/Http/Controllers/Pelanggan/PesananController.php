<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query pesanan milik user yang login
        $query = Pesanan::where('user_id', Auth::id())->latest();

        // Terapkan Filter Jenis Pesanan jika dipilih
        if ($request->filled('jenis')) {
            $query->where('jenis_pesanan', $request->jenis);
        }

        // Terapkan Filter Status Pembayaran jika dipilih
        if ($request->filled('status_bayar')) {
            $query->where('status_pembayaran', $request->status_bayar);
        }

        // Eksekusi query untuk mengambil data
        $pesanans = $query->get();

        return view('pelanggan.pesanan.index', compact('pesanans'));
    }

    public function show(Pesanan $pesanan)
    {
        // Pastikan hanya pemilik pesanan yang bisa melihat
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $pesanan->load('items.barang');
        return view('pelanggan.pesanan.show', compact('pesanan'));
    }
}