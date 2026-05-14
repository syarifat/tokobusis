<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan: {{ $pesanan->kode_pesanan }}
            </h2>
            <a href="{{ route('pelanggan.pesanan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        
                        <div class="flex justify-between items-start border-b pb-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Status Pesanan</p>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $pesanan->status_pesanan == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $pesanan->status_pesanan }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Jenis Transaksi</p>
                                <p class="font-bold text-indigo-600">{{ ucfirst($pesanan->jenis_pesanan) }} {{ $pesanan->nama_event ? '('.$pesanan->nama_event.')' : '' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm bg-gray-50 p-4 rounded-lg border border-gray-100 mb-4">
                            <div>
                                <p class="text-gray-500 text-xs uppercase font-bold tracking-wider mb-1">Metode Pengiriman</p>
                                <p class="font-bold text-gray-900">
                                    @if($pesanan->metode_pengiriman == 'ambil_sendiri')
                                        🏪 Ambil Sendiri ke Toko
                                    @else
                                        🚚 Diantar Kurir Toko
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs uppercase font-bold tracking-wider mb-1">Metode Pembayaran</p>
                                <p class="font-bold text-gray-900">
                                    @if($pesanan->tipe_pembayaran == 'cash')
                                        💵 Tunai (Cash)
                                    @else
                                        💳 Transfer / Non-Tunai
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">{{ $pesanan->metode_pengiriman == 'ambil_sendiri' ? 'Tanggal Pengambilan:' : 'Tanggal Pengantaran:' }}</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($pesanan->tanggal_pengantaran)->format('d M Y') }}</p>
                            </div>
                            @if($pesanan->jenis_pesanan == 'event')
                            <div>
                                <p class="text-gray-500">Durasi Cicilan:</p>
                                <p class="font-semibold">{{ $pesanan->jumlah_cicilan }}x Cicilan</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Tenggat Pelunasan Bon:</p>
                                <p class="font-semibold text-red-600">{{ \Carbon\Carbon::parse($pesanan->tenggat_pembayaran)->format('d M Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-bold text-lg mb-4">Rincian Barang</h3>
                        <div class="space-y-4">
                            @foreach($pesanan->items as $item)
                            <div class="flex items-center justify-between border-b pb-4">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded mr-4 overflow-hidden">
                                        @if($item->barang->gambar)
                                            <img src="{{ asset('storage/'.$item->barang->gambar) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $item->barang->nama_barang }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->qty }} {{ $item->barang->satuan }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <p class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 pt-4 border-t space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal Barang</span>
                                <span>Rp {{ number_format($pesanan->total_harga - $pesanan->ongkir, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Ongkos Kirim</span>
                                <span class="{{ $pesanan->ongkir == 0 ? 'text-green-600 font-bold' : '' }}">
                                    {{ $pesanan->ongkir == 0 ? 'Gratis' : 'Rp ' . number_format($pesanan->ongkir, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-between mt-4 pt-4 border-t-2">
                            <p class="text-lg font-bold">Total Keseluruhan</p>
                            <p class="text-lg font-black text-indigo-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1 space-y-6">
                    @if($pesanan->hitungDenda() > 0)
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                <div>
                                    <h3 class="text-sm font-bold text-red-800">Denda Keterlambatan</h3>
                                    <p class="text-xs text-red-700 mt-1">Pesanan ini telah melewati tenggat pembayaran selama <strong>{{ $pesanan->getMonthsOverdue() }} bulan</strong>. Dikenakan denda sebesar 10% per bulan dari sisa pokok tagihan.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-indigo-500">
                        <h3 class="font-bold text-lg mb-4">Informasi Tagihan</h3>
                        
                        @php
                            $cicilan_sisa = [];
                            $cicilan_dibayar = 0;
                            $nominal_per_cicilan = 0;
                            if ($pesanan->jenis_pesanan == 'event' && $pesanan->jumlah_cicilan > 1) {
                                $nominal_per_cicilan = round($pesanan->total_harga / $pesanan->jumlah_cicilan);
                                $akumulasi = 0;
                                for($i = 1; $i <= $pesanan->jumlah_cicilan; $i++) {
                                    $tagihan = ($i == $pesanan->jumlah_cicilan) ? ($pesanan->total_harga - $akumulasi) : $nominal_per_cicilan;
                                    $akumulasi += $tagihan;
                                    if ($pesanan->total_dibayar >= ($akumulasi - 10)) {
                                        $cicilan_dibayar++;
                                    } else {
                                        $cicilan_sisa[] = ['ke' => $i, 'nominal' => $tagihan];
                                    }
                                }
                            }
                        @endphp

                        @if($pesanan->jenis_pesanan == 'event' && $pesanan->jumlah_cicilan > 1)
                            <div class="mb-4 bg-yellow-50 p-3 rounded border border-yellow-200 text-xs">
                                <p class="font-bold text-yellow-900 mb-1">Progres Cicilan:</p>
                                <p class="text-yellow-800">Sudah dibayar: <strong>{{ $cicilan_dibayar }} dari {{ $pesanan->jumlah_cicilan }} cicilan</strong>.</p>
                                <p class="text-yellow-700 mt-1">Nominal per cicilan: Rp {{ number_format($nominal_per_cicilan, 0, ',', '.') }}</p>
                            </div>
                        @endif

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total Pokok Tagihan:</span>
                                <span class="font-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            @if($pesanan->hitungDenda() > 0)
                            <div class="flex justify-between text-sm text-red-600">
                                <span>Denda Keterlambatan:</span>
                                <span class="font-bold">+ Rp {{ number_format($pesanan->hitungDenda(), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Sudah Dibayar:</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($pesanan->total_dibayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm border-t pt-2">
                                <span class="font-bold">Sisa Tagihan:</span>
                                <span class="font-bold text-red-600" id="sisa-tagihan-val">Rp {{ number_format(($pesanan->total_harga + $pesanan->hitungDenda()) - $pesanan->total_dibayar, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mb-6 text-center">
                            <p class="text-xs text-gray-500 mb-1">Status Pembayaran</p>
                            <span class="px-4 py-1 rounded-full text-sm font-black uppercase {{ $pesanan->status_pembayaran == 'lunas' ? 'bg-green-500 text-white' : 'bg-orange-500 text-white' }}">
                                {{ str_replace('_', ' ', $pesanan->status_pembayaran) }}
                            </span>
                        </div>

                        @if($pesanan->status_pembayaran != 'lunas')
                            
                            @if($pesanan->tipe_pembayaran == 'cash')
                                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg text-center">
                                    <svg class="w-8 h-8 text-blue-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <p class="text-sm text-blue-800 font-bold">Pilih Bayar Tunai</p>
                                    <p class="text-xs text-blue-600 mt-1">
                                        Silakan siapkan uang tunai sejumlah tagihan saat 
                                        {{ $pesanan->metode_pengiriman == 'diantar' ? 'kurir mengantar barang.' : 'mengambil barang di kasir toko.' }}
                                    </p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    <p class="text-xs text-gray-500 italic text-center">*Klik tombol di bawah untuk melakukan pembayaran via Midtrans</p>
                                    
                                    @if($pesanan->jenis_pesanan == 'event' && $pesanan->jumlah_cicilan > 1)
                                        <div class="mt-4">
                                            <label class="block text-xs font-bold mb-1">Pilih Pembayaran Cicilan</label>
                                            <select id="nominal_cicilan" class="w-full border-gray-300 rounded text-sm mb-3 font-bold text-indigo-700 focus:ring-indigo-500">
                                                @php $sum = 0; $denda = $pesanan->hitungDenda(); @endphp
                                                @foreach($cicilan_sisa as $index => $c)
                                                    @php 
                                                        $sum += $c['nominal']; 
                                                        $isLast = ($c['ke'] == $pesanan->jumlah_cicilan);
                                                        $label = ($index == 0) ? 'Cicilan ke-'.$c['ke'] : 'Langsung Cicilan ke-'.$cicilan_sisa[0]['ke'].' s/d ke-'.$c['ke'];
                                                        if ($isLast) {
                                                            if ($denda > 0) $label .= ' (+ Denda)';
                                                            $label .= ' (Pelunasan Akhir)';
                                                            $sum += $denda;
                                                        }
                                                    @endphp
                                                    <option value="{{ $sum }}">{{ $label }} - Rp {{ number_format($sum, 0, ',', '.') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <input type="hidden" id="nominal_cicilan" value="{{ ($pesanan->total_harga + $pesanan->hitungDenda()) - $pesanan->total_dibayar }}">
                                    @endif

                                    <button type="button" id="pay-button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded shadow-lg transition duration-200 mt-2">
                                        Bayar Sekarang
                                    </button>
                                </div>
                            @endif

                        @else
                            <div class="bg-green-50 p-4 rounded text-center border border-green-200 text-green-700 font-bold text-sm">
                                Pesanan ini sudah lunas. Terima kasih!
                            </div>
                        @endif
                    </div>

                    @if($pesanan->jenis_pesanan == 'event' && $pesanan->pembayarans->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-bold text-sm mb-3">Histori Cicilan</h3>
                        <div class="space-y-3">
                            @foreach($pesanan->pembayarans->where('status_transaksi', 'sukses') as $bayar)
                            <div class="text-xs border-l-2 border-green-500 pl-3 py-1">
                                <p class="font-bold">Rp {{ number_format($bayar->nominal_bayar, 0, ',', '.') }}</p>
                                <p class="text-gray-500">{{ $bayar->waktu_bayar ? $bayar->waktu_bayar->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($pesanan->tipe_pembayaran != 'cash')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const payButton = document.getElementById('pay-button');
                
                if (payButton) {
                    payButton.addEventListener('click', function () {
                        const inputNominal = document.getElementById('nominal_cicilan');
                        const sisaTagihan = {{ ($pesanan->total_harga + $pesanan->hitungDenda()) - $pesanan->total_dibayar }};
                        const nominal = inputNominal ? parseInt(inputNominal.value) : sisaTagihan;

                        if (!nominal || nominal < 1000) {
                            alert("Minimal pembayaran adalah Rp 1.000");
                            return;
                        }

                        if (nominal > sisaTagihan) {
                            alert("Nominal bayar tidak boleh melebihi sisa tagihan (Rp " + sisaTagihan.toLocaleString('id-ID') + ")");
                            return;
                        }

                        payButton.disabled = true;
                        payButton.innerText = "Memproses...";

                        fetch("{{ route('pelanggan.pembayaran.pay', $pesanan->id) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({ nominal: nominal })
                        })
                        .then(async response => {
                            const data = await response.json();
                            if (!response.ok) {
                                throw new Error(data.message || "Terjadi kesalahan pada server");
                            }
                            return data;
                        })
                        .then(data => {
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    alert("Pembayaran Berhasil!");
                                    location.reload();
                                },
                                onPending: function(result) {
                                    alert("Menunggu Pembayaran...");
                                    location.reload();
                                },
                                onError: function(result) {
                                    alert("Pembayaran Gagal!");
                                    payButton.disabled = false;
                                    payButton.innerText = "Bayar Sekarang";
                                },
                                onClose: function() {
                                    payButton.disabled = false;
                                    payButton.innerText = "Bayar Sekarang";
                                }
                            });
                        })
                        .catch(error => {
                            alert(error.message);
                            payButton.disabled = false;
                            payButton.innerText = "Bayar Sekarang";
                        });
                    });
                }
            });
        </script>
    @endif
</x-app-layout>