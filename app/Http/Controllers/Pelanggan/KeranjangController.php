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

        if (!Auth::check()) {
            $pendingCart = session()->get('pending_cart', []);
            $found = false;
            foreach ($pendingCart as &$item) {
                if ($item['id'] == $request->barang_id) {
                    $item['qty'] += $request->qty;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $pendingCart[] = ['id' => $request->barang_id, 'qty' => $request->qty];
            }
            session()->put('pending_cart', $pendingCart);
            return redirect()->route('login')->with('status', 'Silakan login untuk melanjutkan pesanan Anda.');
        }

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

    public function bulkStore(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:barangs,id',
            'items.*.qty' => 'required|integer|min:1'
        ]);

        if (!Auth::check()) {
            $pendingCart = session()->get('pending_cart', []);
            foreach ($request->items as $newItem) {
                $found = false;
                foreach ($pendingCart as &$item) {
                    if ($item['id'] == $newItem['id']) {
                        $item['qty'] += $newItem['qty'];
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $pendingCart[] = ['id' => $newItem['id'], 'qty' => $newItem['qty']];
                }
            }
            session()->put('pending_cart', $pendingCart);
            return redirect()->route('login')->with('status', 'Silakan login untuk melanjutkan pesanan Anda.');
        }

        foreach ($request->items as $item) {
            $barangId = $item['id'];
            $qtyInput = $item['qty'];

            // Cek apakah barang sudah ada di keranjang user
            $keranjang = Keranjang::where('user_id', Auth::id())
                ->where('barang_id', $barangId)
                ->first();

            if ($keranjang) {
                // Jika sudah ada, tambahkan qty dari input ke qty yang sudah ada
                $keranjang->increment('qty', $qtyInput);
            } else {
                // Jika belum ada, buat record baru dengan qty sesuai input
                Keranjang::create([
                    'user_id' => Auth::id(),
                    'barang_id' => $barangId,
                    'qty' => $qtyInput
                ]);
            }
        }

        return redirect()->route('pelanggan.keranjang.index')
            ->with('success', count($request->items) . ' jenis barang berhasil ditambahkan!');
    }
}