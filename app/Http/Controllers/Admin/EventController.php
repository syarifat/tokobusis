<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanEvent;
use App\Models\Pesanan; // <-- Tambahkan model Pesanan
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // Admin melihat semua pengajuan
        $events = PengajuanEvent::with('user')->latest()->get();
        return view('admin.event.index', compact('events'));
    }

    public function updateStatus(Request $request, PengajuanEvent $event)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak,selesai'
        ]);

        // LOGIKA KEAMANAN BARU: Jika admin mencoba menyetujui event
        if ($request->status === 'disetujui') {
            
            // 1. Cek apakah user masih punya event lain yang sedang "disetujui"
            $hasActiveEvent = PengajuanEvent::where('user_id', $event->user_id)
                ->where('id', '!=', $event->id)
                ->where('status', 'disetujui')
                ->exists();

            // 2. Cek apakah user masih punya tunggakan Bon dari event sebelumnya
            $hasUnpaidOrder = Pesanan::where('user_id', $event->user_id)
                ->where('jenis_pesanan', 'event')
                ->where('status_pembayaran', '!=', 'lunas')
                ->exists();

            // Jika ada event aktif atau ada tunggakan, tolak persetujuan!
            if ($hasActiveEvent || $hasUnpaidOrder) {
                return redirect()->back()->with('error', 'Gagal disetujui! Pelanggan ini masih memiliki event yang sedang berjalan atau tunggakan Bon yang belum lunas.');
            }
        }

        // Jika aman, lanjutkan update status
        $event->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status event berhasil diubah menjadi ' . $request->status);
    }
}