<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    // Menampilkan halaman kelola stok dan riwayatnya
    public function index()
    {
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        $riwayats = RiwayatStok::with('barang')->latest()->paginate(10);
        
        return view('admin.stok.index', compact('barangs', 'riwayats'));
    }

    // Memproses penambahan/pengurangan stok
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jenis' => 'required|in:masuk,keluar,penyesuaian',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $barang = Barang::findOrFail($request->barang_id);
                $stokSebelum = $barang->stok;
                
                // Hitung stok baru
                if ($request->jenis == 'masuk') {
                    $stokSesudah = $stokSebelum + $request->jumlah;
                } else {
                    $stokSesudah = $stokSebelum - $request->jumlah;
                    // Pastikan stok tidak minus
                    if ($stokSesudah < 0) {
                        throw new \Exception('Stok tidak mencukupi untuk dikeluarkan.');
                    }
                }

                // 1. Update stok di tabel barangs
                $barang->update(['stok' => $stokSesudah]);

                // 2. Catat ke riwayat
                RiwayatStok::create([
                    'barang_id' => $barang->id,
                    'jenis' => $request->jenis,
                    'jumlah' => $request->jumlah,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSesudah,
                    'keterangan' => $request->keterangan
                ]);
            });

            return back()->with('success', 'Stok barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}