<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; } /* Font dikecilkan sedikit agar tabel muat */
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 20px; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        
        .summary { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary td { padding: 10px; border: 1px solid #ddd; text-align: center; background: #f9f9f9; }
        .summary h3 { margin: 0 0 5px 0; font-size: 16px; color: #333; }
        .summary p { margin: 0; font-size: 10px; color: #666; text-transform: uppercase; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #333; padding: 6px; }
        table.data th { background-color: #f2f2f2; text-align: center; font-size: 10px; }
        table.data td { font-size: 10px; }
        table.data .text-right { text-align: right; }
        table.data .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>TOKO BU SIS</h2>
        <p>Laporan Penjualan Periode: <strong>{{ $title }}</strong></p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <p>Total Transaksi</p>
                <h3>{{ $totalTransaksi }}</h3>
            </td>
            <td>
                <p>Total Omzet</p>
                <h3>Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
            </td>
            <td>
                <p>Pendapatan Tunai</p>
                <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            </td>
            <td>
                <p>Total Piutang (Bon)</p>
                <h3>Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h3>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Pesanan</th>
                <th>Pelanggan</th>
                <th>Jenis</th>
                <th>Pengiriman</th>
                <th>Bayar</th>
                <th>Status Bayar</th>
                <th>Total Tagihan</th>
                <th>Dibayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $p->created_at->format('d/m/y') }}</td>
                <td class="text-center">{{ $p->kode_pesanan }}</td>
                <td>{{ $p->user->name }}</td>
                <td class="text-center">{{ strtoupper($p->jenis_pesanan) }}</td>
                
                <td class="text-center">{{ $p->metode_pengiriman == 'ambil_sendiri' ? 'Ambil Toko' : 'Diantar' }}</td>
                <td class="text-center">{{ $p->tipe_pembayaran == 'cash' ? 'Tunai' : 'Transfer' }}</td>
                
                <td class="text-center">{{ strtoupper(str_replace('_', ' ', $p->status_pembayaran)) }}</td>
                <td class="text-right">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($p->total_dibayar, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 20px;">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>