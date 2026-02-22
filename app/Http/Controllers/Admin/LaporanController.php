<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default filter adalah 'bulan'
        $filter = $request->query('filter', 'bulan');
        $now = Carbon::now();

        // Ambil pesanan yang tidak dibatalkan (asumsi pesanan sah)
        $query = Pesanan::with('user')->where('status_pesanan', '!=', 'dibatalkan');

        // Terapkan Filter Waktu
        switch ($filter) {
            case 'hari':
                $query->whereDate('created_at', Carbon::today());
                $title = "Hari Ini (" . Carbon::now()->format('d M Y') . ")";
                break;
            case 'minggu':
                // Gunakan copy() agar variabel asli tidak tertimpa
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                
                $query->whereBetween('created_at', [$start, $end]);
                $title = "Minggu Ini (" . $start->format('d M') . " - " . $end->format('d M Y') . ")";
                break;
            case 'tahun':
                $query->whereYear('created_at', Carbon::now()->year);
                $title = "Tahun " . Carbon::now()->format('Y');
                break;
            case 'bulan':
            default:
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                $title = "Bulan " . Carbon::now()->format('F Y');
                break;
        }

        // Eksekusi Query
        $pesanans = $query->latest()->get();

        // Hitung Ringkasan
        $totalTransaksi = $pesanans->count();
        $totalOmzet = $pesanans->sum('total_harga'); // Total nilai barang yang terjual
        $totalPendapatan = $pesanans->sum('total_dibayar'); // Total uang riil yang sudah diterima
        $totalPiutang = $totalOmzet - $totalPendapatan; // Sisa yang belum dibayar (Bon)

        return view('admin.laporan.index', compact(
            'pesanans', 'filter', 'title', 'totalTransaksi', 'totalOmzet', 'totalPendapatan', 'totalPiutang'
        ));
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->query('filter', 'bulan');
        
        $query = Pesanan::with('user')->where('status_pesanan', '!=', 'dibatalkan');

        // Logika filter sama persis seperti di index
        switch ($filter) {
            case 'hari':
                $query->whereDate('created_at', Carbon::today());
                $title = "Hari Ini (" . Carbon::now()->format('d M Y') . ")";
                break;
            case 'minggu':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                $query->whereBetween('created_at', [$start, $end]);
                $title = "Minggu Ini (" . $start->format('d M') . " - " . $end->format('d M Y') . ")";
                break;
            case 'tahun':
                $query->whereYear('created_at', Carbon::now()->year);
                $title = "Tahun " . Carbon::now()->format('Y');
                break;
            case 'bulan':
            default:
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                $title = "Bulan " . Carbon::now()->format('F Y');
                break;
        }

        $pesanans = $query->latest()->get();
        $totalTransaksi = $pesanans->count();
        $totalOmzet = $pesanans->sum('total_harga');
        $totalPendapatan = $pesanans->sum('total_dibayar');
        $totalPiutang = $totalOmzet - $totalPendapatan;

        // Muat view khusus PDF dan atur ukuran kertas
        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'pesanans', 'title', 'totalTransaksi', 'totalOmzet', 'totalPendapatan', 'totalPiutang'
        ))->setPaper('a4', 'landscape'); // Format kertas A4 memanjang

        // Download otomatis dengan nama file dinamis
        return $pdf->download('Laporan_Penjualan_Toko_Bu_Sis_' . $filter . '.pdf');
    }
}