<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan: <span class="text-indigo-600">{{ $pesanan->kode_pesanan }}</span>
            </h2>
            <a href="{{ route('admin.pesanan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md text-sm transition">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1 space-y-6">
                    
                    @if($pesanan->hitungDenda() > 0)
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm border-r border-t border-b border-red-100">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                <div>
                                    <h3 class="text-sm font-bold text-red-800">Pesanan Menunggak!</h3>
                                    <p class="text-xs text-red-700 mt-1">Pesanan ini telah melewati tenggat pembayaran selama <strong>{{ $pesanan->getMonthsOverdue() }} bulan</strong>. Dikenakan denda sebesar 10% per bulan dari sisa pokok tagihan.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Informasi Pelanggan</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-500 text-xs">Nama Lengkap</p>
                                <p class="font-bold">{{ $pesanan->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs">Nomor WhatsApp</p>
                                <p class="font-bold text-blue-600">{{ $pesanan->user->no_hp ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs">Email</p>
                                <p class="font-bold">{{ $pesanan->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Pengiriman & Lokasi</h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="text-gray-500 text-xs">Metode Pengiriman</p>
                                <p class="font-bold {{ $pesanan->metode_pengiriman == 'ambil_sendiri' ? 'text-orange-600' : 'text-blue-600' }}">
                                    {{ $pesanan->metode_pengiriman == 'ambil_sendiri' ? '🏪 Ambil Sendiri ke Toko' : '🚚 Diantar Kurir Toko' }}
                                </p>
                            </div>

                            @if($pesanan->metode_pengiriman == 'diantar')
                                <div>
                                    <p class="text-gray-500 text-xs">Alamat Tujuan</p>
                                    <p class="font-medium text-gray-800">{{ $pesanan->user->alamat }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-gray-500 text-xs mb-2">Titik Koordinat (GPS)</p>
                                    @if($pesanan->latitude && $pesanan->longitude)
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $pesanan->latitude }},{{ $pesanan->longitude }}" target="_blank" class="flex items-center justify-center w-full bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 font-bold py-2 px-4 rounded-lg transition shadow-sm">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                            Buka di Google Maps
                                        </a>
                                        <p class="text-[10px] text-gray-400 mt-1 text-center font-mono">{{ $pesanan->latitude }}, {{ $pesanan->longitude }}</p>
                                    @else
                                        <div class="bg-red-50 text-red-600 p-2 rounded text-xs text-center border border-red-200 font-bold">
                                            ⚠️ Titik GPS tidak dilampirkan.
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="border-t pt-3">
                                <p class="text-gray-500 text-xs">{{ $pesanan->metode_pengiriman == 'ambil_sendiri' ? 'Tanggal Diambil' : 'Tanggal Diantar' }}</p>
                                <p class="font-bold">{{ \Carbon\Carbon::parse($pesanan->tanggal_pengantaran)->format('d F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Ringkasan Tagihan</h3>
                        
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
                            <div class="mb-4 bg-purple-50 p-3 rounded-lg border border-purple-100 text-xs">
                                <p class="font-bold text-purple-900 mb-1">Progres Cicilan:</p>
                                <p class="text-purple-800">Sudah dibayar: <strong>{{ $cicilan_dibayar }} dari {{ $pesanan->jumlah_cicilan }} cicilan</strong>.</p>
                                <p class="text-purple-700 mt-1">Nominal per cicilan: Rp {{ number_format($nominal_per_cicilan, 0, ',', '.') }}</p>
                            </div>
                        @endif

                        <div class="space-y-3 text-sm mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Pokok Tagihan</span>
                                <span class="font-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            @if($pesanan->hitungDenda() > 0)
                            <div class="flex justify-between text-red-600">
                                <span>Denda Keterlambatan</span>
                                <span class="font-bold">+ Rp {{ number_format($pesanan->hitungDenda(), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-500">Sudah Dibayar</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($pesanan->total_dibayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="font-bold text-gray-800">Sisa Tagihan</span>
                                <span class="font-black text-red-600">Rp {{ number_format(($pesanan->total_harga + $pesanan->hitungDenda()) - $pesanan->total_dibayar, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-center text-xs font-bold mb-4">
                            <div class="p-2 rounded-lg border {{ $pesanan->status_pembayaran == 'lunas' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-orange-50 border-orange-200 text-orange-700' }}">
                                <p class="text-[10px] text-gray-500 font-normal uppercase mb-1">Status Bayar</p>
                                {{ strtoupper($pesanan->status_pembayaran) }}
                            </div>
                            <div class="p-2 rounded-lg border {{ $pesanan->jenis_pesanan == 'event' ? 'bg-purple-50 border-purple-200 text-purple-700' : 'bg-gray-50 border-gray-200 text-gray-700' }}">
                                <p class="text-[10px] text-gray-500 font-normal uppercase mb-1">Jenis Pesanan</p>
                                {{ strtoupper($pesanan->jenis_pesanan) }}
                            </div>
                        </div>

                        <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST" class="mt-4 border-t pt-4">
                            @csrf @method('PUT')
                            <label class="block text-xs text-gray-500 mb-1">Ubah Status Pengiriman</label>
                            <div class="flex gap-2">
                                <select name="status" class="flex-1 text-sm rounded-md border-gray-300 focus:ring-indigo-500">
                                    <option value="menunggu" @if($pesanan->status_pesanan == 'menunggu') selected @endif>Menunggu</option>
                                    <option value="diproses" @if($pesanan->status_pesanan == 'diproses') selected @endif>Diproses</option>
                                    <option value="dikirim" @if($pesanan->status_pesanan == 'dikirim') selected @endif>Dikirim</option>
                                    <option value="selesai" @if($pesanan->status_pesanan == 'selesai') selected @endif>Selesai</option>
                                    <option value="dibatalkan" @if($pesanan->status_pesanan == 'dibatalkan') selected @endif>Batal</option>
                                </select>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-bold transition">Update</button>
                            </div>
                        </form>
                    </div>

                    @if($pesanan->jenis_pesanan == 'event')
                    <div class="bg-purple-50 rounded-xl shadow-sm border border-purple-100 p-6">
                        <h3 class="text-sm font-bold text-purple-900 border-b border-purple-200 pb-2 mb-3">Detail Event (Bon)</h3>
                        <div class="space-y-2 text-sm text-purple-800">
                            <p><strong>Nama Acara:</strong> {{ $pesanan->nama_event }}</p>
                            <p><strong>Tgl Acara:</strong> {{ \Carbon\Carbon::parse($pesanan->tanggal_acara)->format('d F Y') }}</p>
                            <p><strong>Durasi Cicilan:</strong> {{ $pesanan->jumlah_cicilan }}x Cicilan</p>
                            <p class="text-red-600 mt-2"><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($pesanan->tenggat_pembayaran)->format('d F Y') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($pesanan->catatan)
                    <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-4">
                        <p class="text-xs font-bold text-yellow-800 uppercase mb-1">Catatan dari Pelanggan:</p>
                        <p class="text-sm text-yellow-900 italic">"{{ $pesanan->catatan }}"</p>
                    </div>
                    @endif
                </div>

                <div class="md:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Rincian Barang</h3>
                            <span class="text-sm text-gray-500 font-semibold">
                                Tgl {{ $pesanan->metode_pengiriman == 'ambil_sendiri' ? 'Ambil' : 'Antar' }}: {{ \Carbon\Carbon::parse($pesanan->tanggal_pengantaran)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($pesanan->items as $item)
                                <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg border overflow-hidden flex-shrink-0">
                                            @if($item->barang->gambar)
                                                <img src="{{ asset('storage/'.$item->barang->gambar) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">IMG</div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm">{{ $item->barang->nama_barang }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $item->qty }} {{ $item->barang->satuan }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-black text-indigo-600 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
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
                        </div>
                        @if($pesanan->hitungDenda() > 0)
                        <div class="px-6 py-3 border-t border-red-100 flex justify-between items-center bg-red-50 text-red-600 text-sm">
                            <span class="font-bold">Total Denda Keterlambatan</span>
                            <span class="font-bold">+ Rp {{ number_format($pesanan->hitungDenda(), 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-gray-500 font-bold uppercase text-sm">Total Keseluruhan</span>
                            <span class="text-xl font-black text-gray-900">Rp {{ number_format($pesanan->total_harga + $pesanan->hitungDenda(), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900">Riwayat Pembayaran</h3>
                            <span class="text-xs font-bold px-2 py-1 rounded {{ $pesanan->tipe_pembayaran == 'cash' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $pesanan->tipe_pembayaran == 'cash' ? 'METODE: TUNAI' : 'METODE: TRANSFER / MIDTRANS' }}
                            </span>
                        </div>
                        <div class="p-6">
                            
                            @if($pesanan->tipe_pembayaran == 'cash' && $pesanan->status_pembayaran != 'lunas')
                                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-6">
                                    <h4 class="text-sm font-bold text-blue-900 mb-2">Terima Pembayaran Tunai</h4>
                                    <p class="text-xs text-blue-700 mb-3">Pesanan ini menggunakan metode Cash. Masukkan nominal uang yang diterima dari pelanggan (Kasir/Kurir).</p>
                                    
                                    <form action="{{ route('admin.pesanan.bayarTunai', $pesanan->id) }}" method="POST" class="flex flex-col gap-2">
                                        @csrf
                                        @if($pesanan->jenis_pesanan == 'event' && $pesanan->jumlah_cicilan > 1)
                                            <label class="block text-xs font-bold text-blue-900 mb-1">Pilih Pembayaran Cicilan</label>
                                            <select name="nominal" class="w-full border-blue-300 rounded-md text-sm font-bold text-indigo-700 focus:ring-blue-500">
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
                                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 mt-2 rounded-md text-sm font-bold transition">Catat Bayar Tunai</button>
                                        @else
                                            <div class="flex gap-2">
                                                @php $sisaAkhir = ($pesanan->total_harga + $pesanan->hitungDenda()) - $pesanan->total_dibayar; @endphp
                                                <input type="number" name="nominal" class="flex-1 rounded-md border-gray-300 text-sm focus:ring-blue-500" value="{{ $sisaAkhir }}" min="1" max="{{ $sisaAkhir }}" required>
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-bold transition">Catat Bayar</button>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            @endif

                            @if($pesanan->pembayarans->count() > 0)
                                <div class="space-y-4">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Histori Transaksi Berhasil</p>
                                    @foreach($pesanan->pembayarans->where('status_transaksi', 'sukses') as $bayar)
                                    <div class="flex justify-between items-center border-l-4 border-green-500 pl-4 py-2 bg-gray-50 rounded-r-lg">
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($bayar->nominal_bayar, 0, ',', '.') }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1">{{ $bayar->waktu_bayar ? \Carbon\Carbon::parse($bayar->waktu_bayar)->format('d M Y, H:i') : '-' }} • {{ strtoupper($bayar->metode_pembayaran) }}</p>
                                        </div>
                                        <div>
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-700">
                                                SUKSES
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 text-gray-400 text-sm border-t border-dashed mt-2">
                                    <p>Belum ada riwayat pembayaran yang tercatat.</p>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>