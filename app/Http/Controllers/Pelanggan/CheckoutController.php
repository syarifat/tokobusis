<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\PengajuanEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // <-- Tambahan Use Log agar rapi
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\FonnteService;

class CheckoutController extends Controller
{
    // Menampilkan Halaman Checkout
    public function index(Request $request) 
    {
        // Ambil array ID yang dicentang dari form keranjang
        $selectedIds = $request->input('selected_items', []);

        // Jika user mengakses halaman ini tanpa mencentang apapun, kembalikan ke keranjang
        if (empty($selectedIds)) {
            return redirect()->route('pelanggan.keranjang.index')->with('error', 'Pilih minimal satu barang untuk di-checkout.');
        }

        // Hanya ambil keranjang yang ID-nya ada di array $selectedIds
        $keranjangs = Keranjang::with('barang')
            ->where('user_id', Auth::id())
            ->whereIn('id', $selectedIds)
            ->get();
        
        // Cegah akses jika keranjang kosong atau ID yang dikirim tidak valid
        if ($keranjangs->isEmpty()) {
            return redirect()->route('pelanggan.keranjang.index')->with('error', 'Keranjang belanja tidak valid atau kosong.');
        }

        // --- LOGIKA ONGKIR DIMULAI DI SINI ---
        
        // 1. Hitung Subtotal (Hanya harga barang)
        $subtotal = $keranjangs->sum(function($item) {
            return $item->qty * $item->barang->harga;
        });

        // 2. Tentukan Ongkir berdasarkan Subtotal
        $batasGratisOngkir = 150000;
        $tarifOngkir = 10000;

        if ($subtotal >= $batasGratisOngkir) {
            $ongkir = 0; // Gratis Ongkir
        } else {
            $ongkir = $tarifOngkir; // Kena Ongkir Flat
        }

        // 3. Hitung Grand Total
        $totalKeseluruhan = $subtotal + $ongkir;

        // Cek apakah user punya event yang DISETUJUI
        $eventsDisetujui = PengajuanEvent::where('user_id', Auth::id())
                                        ->where('status', 'disetujui')
                                        ->get();

        // Lempar semua variabel ke View
        return view('pelanggan.checkout.index', compact(
            'keranjangs', 'subtotal', 'ongkir', 'batasGratisOngkir', 'totalKeseluruhan', 'eventsDisetujui'
        ));
    }

    // Memproses Data Checkout ke Database
    public function process(Request $request)
    {
        $request->validate([
            'selected_items'      => 'required|array', 
            'selected_items.*'    => 'exists:keranjangs,id',
            'tanggal_pengantaran' => 'required|date|after_or_equal:today|before_or_equal:+1 month',
            'jenis_pesanan'       => 'required|in:reguler,event',
            'event_id'            => 'required_if:jenis_pesanan,event',
            'jumlah_cicilan'      => 'required_if:jenis_pesanan,event|in:1,3,6',
            'metode_pengiriman'   => 'required|in:diantar,ambil_sendiri',
            'tipe_pembayaran'     => 'required|in:transfer,cash',
            'latitude'            => 'nullable|string', // Validasi Maps Baru
            'longitude'           => 'nullable|string', // Validasi Maps Baru
            'catatan'             => 'nullable|string'
        ]);

        $keranjangs = Keranjang::with('barang')
            ->where('user_id', Auth::id())
            ->whereIn('id', $request->selected_items)
            ->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('pelanggan.dashboard')->with('error', 'Tidak ada barang yang dipilih untuk checkout.');
        }

        $subtotal = $keranjangs->sum(function($item) {
            return $item->qty * $item->barang->harga;
        });
        
        if ($request->metode_pengiriman == 'ambil_sendiri') {
            $ongkir = 0;
        } else {
            $ongkir = $subtotal >= 150000 ? 0 : 10000;
        }
        
        $totalKeseluruhan = $subtotal + $ongkir;

        $kode_pesanan = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $dataPesanan = [
            'user_id'             => Auth::id(),
            'kode_pesanan'        => $kode_pesanan,
            'jenis_pesanan'       => $request->jenis_pesanan,
            'tanggal_pengantaran' => $request->tanggal_pengantaran,
            'total_harga'         => $totalKeseluruhan, 
            'ongkir'              => $ongkir,           
            'metode_pengiriman'   => $request->metode_pengiriman,
            'tipe_pembayaran'     => $request->tipe_pembayaran,
            'latitude'            => $request->latitude,   // Simpan ke DB
            'longitude'           => $request->longitude,  // Simpan ke DB
            'total_dibayar'       => 0,
            'status_pesanan'      => 'menunggu',
            'status_pembayaran'   => 'belum_bayar',
            'catatan'             => $request->catatan,
        ];

        if ($request->jenis_pesanan == 'event') {
            $event = PengajuanEvent::findOrFail($request->event_id);
            if ($event->user_id !== Auth::id() || $event->status !== 'disetujui') {
                abort(403, 'Event tidak valid.');
            }
            $dataPesanan['nama_event'] = $event->nama_acara;
            $dataPesanan['tanggal_acara'] = $event->tanggal_acara;
            $dataPesanan['tenggat_pembayaran'] = Carbon::parse($event->tanggal_acara)->addMonths(1);
            $dataPesanan['jumlah_cicilan'] = $request->jumlah_cicilan;
        }

        // 1. Simpan ke database
        $pesanan = Pesanan::create($dataPesanan);

        // 2. Pindahkan item & kurangi stok
        foreach ($keranjangs as $item) {
            PesananItem::create([
                'pesanan_id'   => $pesanan->id,
                'barang_id'    => $item->barang_id,
                'qty'          => $item->qty,
                'harga_satuan' => $item->barang->harga,
                'subtotal'     => $item->qty * $item->barang->harga,
            ]);
            $item->barang->decrement('stok', $item->qty);
        }

        // 3. Kosongkan HANYA Keranjang yang Dipilih
        Keranjang::where('user_id', Auth::id())
            ->whereIn('id', $request->selected_items)
            ->delete();

        // 4. KIRIM WA DENGAN LINK MAPS
        try {
            $wa = new FonnteService();
            $admin = \App\Models\User::where('role', 'admin')->first();

            if ($admin && $admin->no_hp) {
                $nomorAdmin = $admin->no_hp; 
                
                $pengiriman = $request->metode_pengiriman == 'ambil_sendiri' ? '🏪 Ambil Sendiri' : '🚚 Diantar Kurir';
                $pembayaran = $request->tipe_pembayaran == 'cash' ? '💵 Tunai (Cash)' : '💳 Transfer/Online';

                // Format URL API Google Maps Search yang universal (Bisa dibuka di Web / Aplikasi Maps HP)
                $linkMaps = ($pesanan->latitude && $pesanan->longitude) 
                            ? "https://www.google.com/maps/search/?api=1&query={$pesanan->latitude},{$pesanan->longitude}" 
                            : "- (Tidak disertakan / Ambil Sendiri)";

                $pesanAdmin = "🔔 *PESANAN BARU MASUK*\n\n" .
                            "Kode: {$pesanan->kode_pesanan}\n" .
                            "Pelanggan: " . Auth::user()->name . "\n" .
                            "Jenis: " . ucfirst($pesanan->jenis_pesanan) . "\n" .
                            "Pengiriman: {$pengiriman}\n" .
                            "📍 Lokasi Maps: {$linkMaps}\n" .
                            "Pembayaran: {$pembayaran}\n" .
                            "Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n" .
                            "Ongkir: Rp " . number_format($ongkir, 0, ',', '.') . "\n" .
                            "Total Tagihan: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . "\n\n" .
                            "Silakan cek dashboard admin untuk proses lebih lanjut.";
                            
                $wa->sendMessage($nomorAdmin, $pesanAdmin);
            }
        } catch (\Exception $e) {
            \Log::error('Gagal kirim WA Admin: ' . $e->getMessage()); 
        }

        // 5. BARU RETURN
        return redirect()
        ->route('pelanggan.pesanan.show', $pesanan->id)
        ->with('success', 'Pesanan berhasil dibuat! Kode Pesanan: ' . $kode_pesanan);
    }
}