<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Services\FonnteService;
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
        $statusBayar = $pesanan->syncStatusPembayaran();

        // 4. Kirim WA Notifikasi ke Pelanggan
        try {
            $wa = new FonnteService();
            $pesanUser = "✅ *PEMBAYARAN DITERIMA*\n\n" .
                        "Halo {$pesanan->user->name}, pembayaran (Tunai) untuk pesanan *{$pesanan->kode_pesanan}* sebesar Rp " . number_format($nominal, 0, ',', '.') . " telah kami terima.\n";

            if ($pesanan->jenis_pesanan == 'event') {
                $nom_cicil = $pesanan->total_harga / max(1, $pesanan->jumlah_cicilan);
                $x_paid = floor($pesanan->total_dibayar / max(1, $nom_cicil));
                $pesanUser .= "\n*INFO CICILAN*\n" .
                              "Progres: {$x_paid} dari {$pesanan->jumlah_cicilan} cicilan lunas\n";
            }

            $sisaTagihanUpdate = ($pesanan->total_harga + $denda) - $pesanan->total_dibayar;
            $pesanUser .= "\nSisa Tagihan: Rp " . number_format($sisaTagihanUpdate, 0, ',', '.') . "\n" .
                        "Status: " . strtoupper($statusBayar) . "\n\n" .
                        "Terima kasih telah berbelanja di Toko Bu Sis!";

            $wa->sendMessage($pesanan->user->no_hp, $pesanUser);
        } catch (\Exception $e) {
            \Log::error('Gagal kirim WA Pelanggan (Tunai): ' . $e->getMessage()); 
        }

        return back()->with('success', 'Pembayaran tunai sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' berhasil dicatat.');
    }
}