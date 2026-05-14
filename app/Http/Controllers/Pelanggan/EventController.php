<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\PengajuanEvent;
use App\Models\Pesanan; // <-- Tambahan Model Pesanan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        // Melihat riwayat pengajuan event milik pelanggan ini
        $events = PengajuanEvent::where('user_id', Auth::id())->latest()->get();

        // LOGIKA BARU: Cek apakah ada pengajuan event yang masih aktif (belum selesai/ditolak)
        $hasActiveEvent = PengajuanEvent::where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        // LOGIKA BARU: Cek apakah ada pesanan (Bon) dari event sebelumnya yang BELUM LUNAS
        $hasUnpaidOrder = Pesanan::where('user_id', Auth::id())
            ->where('jenis_pesanan', 'event')
            ->where('status_pembayaran', '!=', 'lunas')
            ->exists();

        // LOGIKA BARU: Jika tidak ada event aktif dan tidak ada tunggakan Bon, berarti BISA mengajukan
        $canApply = !($hasActiveEvent || $hasUnpaidOrder);

        // Oper variabel canApply ke view
        return view('pelanggan.event.index', compact('events', 'canApply'));
    }

    public function store(Request $request)
    {
        // LOGIKA BARU: Validasi keamanan di backend agar tidak bisa bypass via API
        $hasActiveEvent = PengajuanEvent::where('user_id', Auth::id())->whereIn('status', ['menunggu', 'disetujui'])->exists();
        $hasUnpaidOrder = Pesanan::where('user_id', Auth::id())->where('jenis_pesanan', 'event')->where('status_pembayaran', '!=', 'lunas')->exists();

        if ($hasActiveEvent || $hasUnpaidOrder) {
            return redirect()->back()->with('error', 'Gagal! Kamu masih memiliki pengajuan event yang aktif atau Bon yang belum lunas.');
        }

        // LOGIKA LAMA (Sesuai kode asli kamu)
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'tanggal_acara' => 'required|date|after:today|before_or_equal:+1 month',
            'keterangan' => 'nullable|string'
        ]);

        PengajuanEvent::create([
            'user_id' => Auth::id(),
            'nama_acara' => $request->nama_acara,
            'tanggal_acara' => $request->tanggal_acara,
            'keterangan' => $request->keterangan,
            'status' => 'menunggu'
        ]);

        return redirect()->back()->with('success', 'Pengajuan Event berhasil dikirim. Silakan tunggu persetujuan Admin.');
    }
}