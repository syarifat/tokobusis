<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    public function pay(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1000'
        ]);

        // Cek sisa tagihan
        $sisaTagihan = ($pesanan->total_harga + $pesanan->hitungDenda()) - $pesanan->total_dibayar;
        if ($request->nominal > $sisaTagihan) {
            return response()->json(['message' => 'Nominal melebihi sisa tagihan'], 422);
        }

        // Buat record pembayaran baru (Status awal: pending)
        $pembayaran = Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'kode_pembayaran' => 'PAY-' . strtoupper(Str::random(10)),
            'nominal_bayar' => $request->nominal,
            'status_transaksi' => 'pending'
        ]);

        // Panggil Service Midtrans
        $midtrans = new MidtransService();
        $snapToken = $midtrans->getSnapToken($pembayaran, Auth::user());

        // Simpan token ke database
        $pembayaran->update(['snap_token' => $snapToken]);

        return response()->json(['snap_token' => $snapToken]);
    }
}