<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handler(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pembayaran = Pembayaran::where('kode_pembayaran', $request->order_id)->first();
        if (!$pembayaran) return response()->json(['message' => 'Not found'], 404);

        $status = $request->transaction_status;
        if ($status == 'settlement' || $status == 'capture') {
            $pembayaran->update([
                'status_transaksi' => 'sukses',
                'metode_pembayaran' => $request->payment_type,
                'waktu_bayar' => now()
            ]);

            // Update akumulasi di tabel pesanan
            $pesanan = $pembayaran->pesanan;
            $newTotalDibayar = $pesanan->total_dibayar + $pembayaran->nominal_bayar;
            
            $statusBayar = ($newTotalDibayar >= $pesanan->total_harga) ? 'lunas' : 'cicilan';

            $pesanan->update([
                'total_dibayar' => $newTotalDibayar,
                'status_pembayaran' => $statusBayar
            ]);
        }

        return response()->json(['message' => 'Success']);
    }
}