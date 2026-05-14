<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dengan eager loading user
        $query = Pesanan::with('user')->latest();

        // 1. Filter Jenis Pesanan
        if ($request->filled('jenis')) {
            $query->where('jenis_pesanan', $request->jenis);
        }

        // 2. Filter Status Pembayaran
        if ($request->filled('status_bayar')) {
            $query->where('status_pembayaran', $request->status_bayar);
        }

        // 3. Filter Rentang Tanggal (Start Date & End Date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Kita gunakan whereBetween untuk rentang waktu. 
            // Tambahkan waktu 23:59:59 di end_date agar pesanan di hari tersebut ikut terambil
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $pesanans = $query->get();

        return view('admin.pesanan.index', compact('pesanans'));
    }

    // FUNGSI BARU UNTUK MENAMPILKAN DETAIL
    public function show(Pesanan $pesanan)
    {
        // Load relasi agar query lebih efisien
        $pesanan->load(['user', 'items.barang', 'pembayarans']);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Pastikan validasinya menggunakan kata 'diproses'
        $request->validate([
            'status' => 'required|in:menunggu,diproses,dikirim,selesai,dibatalkan'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        
        $pesanan->update([
            'status_pesanan' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // Method baru untuk mencatat pembayaran tunai manual oleh Admin
    public function bayarTunai(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // Pastikan hanya bisa untuk metode pembayaran cash
        if ($pesanan->tipe_pembayaran !== 'cash') {
            return back()->with('error', 'Hanya pesanan dengan tipe pembayaran tunai (cash) yang bisa dikonfirmasi manual.');
        }

        $request->validate([
            'nominal' => 'required|numeric|min:1',
        ]);

        $nominal = $request->nominal;
        $denda = $pesanan->hitungDenda();
        $sisaTagihan = ($pesanan->total_harga + $denda) - $pesanan->total_dibayar;

        // Validasi agar tidak lebih bayar
        if ($nominal > $sisaTagihan) {
            return back()->with('error', 'Nominal tidak boleh melebihi sisa tagihan (Rp ' . number_format($sisaTagihan, 0, ',', '.') . ').');
        }

        // 1. Buat record di tabel pembayarans
        $pesanan->pembayarans()->create([
            'kode_pembayaran'   => 'CASH-' . time(),
            'nominal_bayar'     => $nominal,
            'metode_pembayaran' => 'tunai/kasir',
            'status_transaksi'  => 'sukses',
            'waktu_bayar'       => now(),
        ]);

        // 2. Update akumulasi total_dibayar di tabel pesanans
        $totalDibayarBaru = $pesanan->total_dibayar + $nominal;
        
        $pesanan->update([
            'total_dibayar' => $totalDibayarBaru,
        ]);

        // 3. Sinkronisasi status pembayaran di database
        $pesanan->syncStatusPembayaran();

        return back()->with('success', 'Pembayaran tunai sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' berhasil dicatat.');
    }
}