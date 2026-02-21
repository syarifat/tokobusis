<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\PengajuanEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        // Melihat riwayat pengajuan event milik pelanggan ini
        $events = PengajuanEvent::where('user_id', Auth::id())->latest()->get();
        return view('pelanggan.event.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'tanggal_acara' => 'required|date|after:today',
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