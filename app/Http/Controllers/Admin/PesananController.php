<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with('user')->latest()->get();
        return view('admin.pesanan.index', compact('pesanans'));
    }

    // FUNGSI BARU UNTUK MENAMPILKAN DETAIL
    public function show(Pesanan $pesanan)
    {
        // Load relasi agar query lebih efisien
        $pesanan->load(['user', 'items.barang', 'pembayarans']);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $pesanan->update(['status_pesanan' => $request->status]);
        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}