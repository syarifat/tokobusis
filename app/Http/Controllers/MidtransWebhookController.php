<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Services\FonnteService;

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
            
            $pesanan->update([
                'total_dibayar' => $newTotalDibayar,
            ]);
            
            $statusBayar = $pesanan->syncStatusPembayaran();
            $sisaKirim = $pesanan->sisa_tagihan;

            // Kirim WA Notifikasi ke Pelanggan
            $wa = new FonnteService();
            $pesanUser = "✅ *PEMBAYARAN DITERIMA*\n\n" .
                        "Halo {$pesanan->user->name}, pembayaran untuk pesanan *{$pesanan->kode_pesanan}* sebesar Rp " . number_format($pembayaran->nominal_bayar, 0, ',', '.') . " telah kami terima.\n";

            if ($pesanan->jenis_pesanan == 'event') {
                $nom_cicil = $pesanan->total_harga / max(1, $pesanan->jumlah_cicilan);
                $x_paid = floor($pesanan->total_dibayar / max(1, $nom_cicil));
                $pesanUser .= "\n*INFO CICILAN*\n" .
                              "Progres: {$x_paid} dari {$pesanan->jumlah_cicilan} cicilan lunas\n";
            }

            $pesanUser .= "\nSisa Tagihan: Rp " . number_format($sisaKirim, 0, ',', '.') . "\n" .
                        "Status: " . strtoupper($statusBayar) . "\n\n" .
                        "Terima kasih telah berbelanja di Toko Bu Sis!";

            $wa->sendMessage($pesanan->user->no_hp, $pesanUser);
        }

        return response()->json(['message' => 'Success']);
    }
}