<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Pesanan Saya</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100 flex flex-col md:flex-row gap-4 items-end md:items-center justify-between">
                        <form action="{{ route('pelanggan.pesanan.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-4 items-center">
                            
                            <div class="w-full sm:w-auto flex-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Pesanan</label>
                                <select name="jenis" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Semua Jenis</option>
                                    <option value="reguler" {{ request('jenis') == 'reguler' ? 'selected' : '' }}>Reguler (Biasa)</option>
                                    <option value="event" {{ request('jenis') == 'event' ? 'selected' : '' }}>Event (Bon)</option>
                                </select>
                            </div>

                            <div class="w-full sm:w-auto flex-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Pembayaran</label>
                                <select name="status_bayar" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="belum_bayar" {{ request('status_bayar') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="cicilan" {{ request('status_bayar') == 'cicilan' ? 'selected' : '' }}>Cicilan (Sebagian)</option>
                                    <option value="lunas" {{ request('status_bayar') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                </select>
                            </div>

                            <div class="w-full sm:w-auto flex flex-row gap-2 mt-1">
                                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow-sm transition text-sm">
                                    Terapkan Filter
                                </button>
                                
                                @if(request()->has('jenis') || request()->has('status_bayar'))
                                    <a href="{{ route('pelanggan.pesanan.index') }}" class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-md text-center transition text-sm flex items-center justify-center">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Kode Pesanan</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Tanggal</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Jenis</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Total</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Status Bayar</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Status Pesanan</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesanans as $p)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="font-mono font-bold text-indigo-600">{{ $p->kode_pesanan }}</p>
                                    </td>

                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                        <p class="font-bold text-gray-700">{{ $p->created_at->format('d/m/Y') }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $p->created_at->format('H:i') }} WIB</p>
                                    </td>

                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <div class="mb-2">
                                            @if($p->jenis_pesanan == 'event')
                                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold bg-purple-100 text-purple-800 uppercase">
                                                    {{ $p->jenis_pesanan }}
                                                </span>
                                                <p class="text-[10px] text-gray-500 font-bold mt-1.5 line-clamp-2" title="{{ $p->nama_event }}">
                                                    - {{ $p->nama_event }}
                                                </p>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-800 uppercase">
                                                    {{ $p->jenis_pesanan }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-2 pt-2 border-t border-gray-100">
                                            <p class="text-[10px] font-bold {{ $p->metode_pengiriman == 'ambil_sendiri' ? 'text-orange-600' : 'text-blue-600' }}">
                                                {{ $p->metode_pengiriman == 'ambil_sendiri' ? '🏪 Ambil Sendiri' : '🚚 Diantar' }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 mt-0.5">
                                                Via {{ $p->tipe_pembayaran == 'cash' ? 'Tunai (Cash)' : 'Transfer' }}
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold">
                                        Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                    </td>
                                    
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                        @if($p->status_pembayaran == 'lunas')
                                            <span class="text-green-600 font-bold text-xs uppercase">Lunas</span>
                                        @elseif($p->status_pembayaran == 'cicilan')
                                            <span class="text-orange-600 font-bold text-xs uppercase">Cicilan</span>
                                            <p class="text-[10px] text-gray-500 mt-1 font-semibold">Sisa: Rp {{ number_format($p->total_harga - $p->total_dibayar, 0, ',', '.') }}</p>
                                        @else
                                            <span class="text-red-600 font-bold text-xs uppercase">Belum Bayar</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                        <span class="text-xs font-semibold px-2 py-1 bg-gray-100 rounded border border-gray-200">{{ strtoupper($p->status_pesanan) }}</span>
                                    </td>

                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                        <a href="{{ route('pelanggan.pesanan.show', $p->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-md font-bold text-xs transition-colors duration-200 border border-indigo-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="p-5 text-center text-gray-500">Tidak ada pesanan yang sesuai dengan filter.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>