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
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Ringkasan Pembayaran</h3>
                        
                        <div class="space-y-3 text-sm mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Harga</span>
                                <span class="font-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Sudah Dibayar</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($pesanan->total_dibayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="font-bold text-gray-800">Sisa Tagihan</span>
                                <span class="font-black text-red-600">Rp {{ number_format($pesanan->total_harga - $pesanan->total_dibayar, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-center text-xs font-bold mb-4">
                            <div class="p-2 rounded-lg border {{ $pesanan->status_pembayaran == 'lunas' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-orange-50 border-orange-200 text-orange-700' }}">
                                <p class="text-[10px] text-gray-500 font-normal uppercase mb-1">Status Bayar</p>
                                {{ strtoupper($pesanan->status_pembayaran) }}
                            </div>
                            <div class="p-2 rounded-lg border {{ $pesanan->jenis_pesanan == 'event' ? 'bg-purple-50 border-purple-200 text-purple-700' : 'bg-blue-50 border-blue-200 text-blue-700' }}">
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
                            <p class="text-red-600 mt-2"><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($pesanan->tenggat_pembayaran)->format('d F Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Rincian Barang</h3>
                            <span class="text-sm text-gray-500 font-semibold">Tgl Antar: {{ \Carbon\Carbon::parse($pesanan->tanggal_pengantaran)->format('d/m/Y') }}</span>
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
                        </div>
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-gray-500 font-bold uppercase text-sm">Total Keseluruhan</span>
                            <span class="text-xl font-black text-gray-900">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Riwayat Pembayaran Midtrans</h3>
                        </div>
                        <div class="p-6">
                            @if($pesanan->pembayarans->count() > 0)
                                <div class="space-y-4">
                                    @foreach($pesanan->pembayarans as $bayar)
                                    <div class="flex justify-between items-center border-l-4 {{ $bayar->status_transaksi == 'sukses' ? 'border-green-500' : 'border-yellow-400' }} pl-4 py-2 bg-gray-50 rounded-r-lg">
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($bayar->nominal_bayar, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Kode: {{ $bayar->kode_pembayaran }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1">{{ $bayar->waktu_bayar ? \Carbon\Carbon::parse($bayar->waktu_bayar)->format('d M Y, H:i') : 'Menunggu pembayaran' }} • {{ strtoupper($bayar->metode_pembayaran ?? 'Online') }}</p>
                                        </div>
                                        <div>
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $bayar->status_transaksi == 'sukses' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                {{ $bayar->status_transaksi }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 text-gray-400 text-sm">
                                    <p>Belum ada riwayat pembayaran untuk pesanan ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($pesanan->catatan)
                    <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-4">
                        <p class="text-xs font-bold text-yellow-800 uppercase mb-1">Catatan dari Pelanggan:</p>
                        <p class="text-sm text-yellow-900 italic">"{{ $pesanan->catatan }}"</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>