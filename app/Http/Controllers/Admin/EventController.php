<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanEvent;
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

        $event->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status event berhasil diubah menjadi ' . $request->status);
    }
}