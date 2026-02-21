<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $keranjangs = Keranjang::with('barang')->where('user_id', Auth::id())->get();
        return view('pelanggan.keranjang.index', compact('keranjangs'));
    }

    // Menambah barang ke keranjang
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'qty' => 'required|integer|min:1'
        ]);

        // Cek apakah barang sudah ada di keranjang user ini
        $keranjang = Keranjang::where('user_id', Auth::id())
                              ->where('barang_id', $request->barang_id)
                              ->first();

        if ($keranjang) {
            // Jika sudah ada, tambahkan qty-nya
            $keranjang->update([
                'qty' => $keranjang->qty + $request->qty
            ]);
        } else {
            // Jika belum ada, buat record baru
            Keranjang::create([
                'user_id' => Auth::id(),
                'barang_id' => $request->barang_id,
                'qty' => $request->qty
            ]);
        }

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

    // Mengupdate qty di keranjang
    public function update(Request $request, Keranjang $keranjang)
    {
        // Pastikan keranjang ini milik user yang sedang login
        if ($keranjang->user_id !== Auth::id()) {
            abort(403);
        }

        $keranjang->update(['qty' => $request->qty]);

        return redirect()->back()->with('success', 'Jumlah barang diperbarui!');
    }

    // Menghapus barang dari keranjang
    public function destroy(Keranjang $keranjang)
    {
        if ($keranjang->user_id !== Auth::id()) {
            abort(403);
        }

        $keranjang->delete();

        return redirect()->back()->with('success', 'Barang dihapus dari keranjang.');
    }
}