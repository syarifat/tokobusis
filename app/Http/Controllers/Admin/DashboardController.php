<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Statistik Utama
        $totalPendapatan = Pesanan::where('status_pembayaran', 'lunas')->sum('total_dibayar');
        $pesananMenunggu = Pesanan::whereIn('status_pesanan', ['menunggu', 'proses'])->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $barangStokTipisCount = Barang::where('stok', '<=', 5)->count();

        // 2. Ambil Data Detail
        $pesananTerbaru = Pesanan::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $barangStokTipis = Barang::where('stok', '<=', 5)->orderBy('stok', 'asc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPendapatan', 
            'pesananMenunggu', 
            'totalPelanggan', 
            'barangStokTipisCount',
            'pesananTerbaru',
            'barangStokTipis'
        ));
    }
}