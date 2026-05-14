<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filtering Tanggal
        $period = $request->input('period', 'daily'); // daily, monthly
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();

        // 2. Statistik Utama (Cards)
        $totalPendapatan = Pesanan::where('status_pembayaran', 'lunas')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->sum('total_dibayar');
        
        $pesananMenunggu = Pesanan::whereIn('status_pesanan', ['menunggu', 'proses'])->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $barangStokTipisCount = Barang::where('stok', '<=', 5)->count();

        // 3. Data Grafik Pendapatan
        $format = $period == 'monthly' ? '%Y-%m' : '%Y-%m-%d';
        $groupBy = $period == 'monthly' ? 'month' : 'date';

        $revenueData = Pesanan::where('status_pembayaran', 'lunas')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(updated_at, '$format') as label"),
                DB::raw("SUM(total_harga) as pure_revenue"),
                DB::raw("SUM(CASE WHEN total_dibayar > total_harga THEN total_dibayar - total_harga ELSE 0 END) as penalty_revenue")
            )
            ->groupBy('label')
            ->orderBy('label', 'asc')
            ->get();

        // 4. Statistik Pelanggan Teraktif
        // Sering Beli (Semua jenis pesanan)
        $topBuyers = Pesanan::whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id', DB::raw('COUNT(*) as total_pesanan'), DB::raw('SUM(total_dibayar) as total_spent'))
            ->groupBy('user_id')
            ->orderByDesc('total_pesanan')
            ->take(5)
            ->with('user')
            ->get();

        // Sering Bon/Event
        $topEventCustomers = Pesanan::where('jenis_pesanan', 'event')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id', DB::raw('COUNT(*) as total_event'))
            ->groupBy('user_id')
            ->orderByDesc('total_event')
            ->take(5)
            ->with('user')
            ->get();

        // 5. Data Tambahan (Existing)
        $pesananTerbaru = Pesanan::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $barangStokTipis = Barang::where('stok', '<=', 5)->orderBy('stok', 'asc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPendapatan', 
            'pesananMenunggu', 
            'totalPelanggan', 
            'barangStokTipisCount',
            'pesananTerbaru',
            'barangStokTipis',
            'revenueData',
            'topBuyers',
            'topEventCustomers',
            'startDate',
            'endDate',
            'period'
        ));
    }
}