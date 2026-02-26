<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\PengajuanEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // Menampilkan Halaman Checkout
    public function index()
    {
        $keranjangs = Keranjang::with('barang')->where('user_id', Auth::id())->get();
        
        // Cegah akses jika keranjang kosong
        if ($keranjangs->isEmpty()) {
            return redirect()->route('pelanggan.keranjang.index')->with('error', 'Keranjang belanja masih kosong.');
        }

        $totalKeseluruhan = $keranjangs->sum(function($item) {
            return $item->qty * $item->barang->harga;
        });

        // Cek apakah user punya event yang DISETUJUI
        $eventsDisetujui = PengajuanEvent::where('user_id', Auth::id())
                                         ->where('status', 'disetujui')
                                         ->get();

        return view('pelanggan.checkout.index', compact('keranjangs', 'totalKeseluruhan', 'eventsDisetujui'));
    }

    // Memproses Data Checkout ke Database
    public function process(Request $request)
    {
        $request->validate([
            'tanggal_pengantaran' => 'required|date|after_or_equal:today',
            'jenis_pesanan'       => 'required|in:reguler,event',
            'event_id'            => 'required_if:jenis_pesanan,event',
            'catatan'             => 'nullable|string'
        ]);

        $keranjangs = Keranjang::with('barang')->where('user_id', Auth::id())->get();
        if ($keranjangs->isEmpty()) {
            return redirect()->route('pelanggan.dashboard');
        }

        $totalKeseluruhan = $keranjangs->sum(function($item) {
            return $item->qty * $item->barang->harga;
        });

        // Buat Kode Pesanan Unik (ORD-YYYYMMDD-RANDOM)
        $kode_pesanan = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // Persiapkan data pesanan
        $dataPesanan = [
            'user_id'             => Auth::id(),
            'kode_pesanan'        => $kode_pesanan,
            'jenis_pesanan'       => $request->jenis_pesanan,
            'tanggal_pengantaran' => $request->tanggal_pengantaran,
            'total_harga'         => $totalKeseluruhan,
            'total_dibayar'       => 0,
            'status_pesanan'      => 'menunggu',
            'status_pembayaran'   => 'belum_bayar',
            'catatan'             => $request->catatan,
        ];

        // Jika user memilih Bon/Event
        if ($request->jenis_pesanan == 'event') {
            $event = PengajuanEvent::findOrFail($request->event_id);
            
            // Validasi kepemilikan event
            if ($event->user_id !== Auth::id() || $event->status !== 'disetujui') {
                abort(403, 'Event tidak valid.');
            }

            $dataPesanan['nama_event'] = $event->nama_acara;
            $dataPesanan['tanggal_acara'] = $event->tanggal_acara;
            
            // Tenggat pembayaran otomatis diset 7 hari SETELAH tanggal acara
            $dataPesanan['tenggat_pembayaran'] = Carbon::parse($event->tanggal_acara)->addDays(7);
        }

        // 1. Simpan ke tabel pesanans
        $pesanan = Pesanan::create($dataPesanan);

        // 2. Pindahkan isi Keranjang ke PesananItem
        foreach ($keranjangs as $item) {
            PesananItem::create([
                'pesanan_id'   => $pesanan->id,
                'barang_id'    => $item->barang_id,
                'qty'          => $item->qty,
                'harga_satuan' => $item->barang->harga,
                'subtotal'     => $item->qty * $item->barang->harga,
            ]);

            // Kurangi stok barang utama
            $item->barang->decrement('stok', $item->qty);
        }

        // 3. Kosongkan Keranjang
        Keranjang::where('user_id', Auth::id())->delete();

        // Nanti ini kita arahkan ke halaman detail pesanan/pembayaran (Midtrans)
        return redirect()->route('pelanggan.dashboard')->with('success', 'Pesanan berhasil dibuat! Kode Pesanan: ' . $kode_pesanan);
    }
}