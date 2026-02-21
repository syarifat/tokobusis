<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
                    <div class="p-4 bg-green-100 rounded-xl text-green-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Pendapatan</p>
                        <h4 class="text-2xl font-black text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
                    <div class="p-4 bg-blue-100 rounded-xl text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Pesanan Aktif</p>
                        <h4 class="text-2xl font-black text-gray-900">{{ $pesananMenunggu }} <span class="text-sm font-normal text-gray-500">Antrean</span></h4>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
                    <div class="p-4 bg-purple-100 rounded-xl text-purple-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Pelanggan</p>
                        <h4 class="text-2xl font-black text-gray-900">{{ $totalPelanggan }} <span class="text-sm font-normal text-gray-500">Orang</span></h4>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
                    <div class="p-4 bg-red-100 rounded-xl text-red-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Stok Menipis (<= 5)</p>
                        <h4 class="text-2xl font-black text-red-600">{{ $barangStokTipisCount }} <span class="text-sm font-normal text-red-400">Barang</span></h4>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-800">Pesanan Terbaru</h3>
                        <a href="{{ route('admin.pesanan.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">Lihat Semua &rarr;</a>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pesananTerbaru as $pesanan)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-indigo-600">
                                            {{ $pesanan->kode_pesanan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            {{ $pesanan->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($pesanan->status_pesanan == 'menunggu')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-md text-xs font-bold">Menunggu</span>
                                            @elseif($pesanan->status_pesanan == 'proses')
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-md text-xs font-bold">Diproses</span>
                                            @elseif($pesanan->status_pesanan == 'selesai')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-md text-xs font-bold">Selesai</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-md text-xs font-bold">{{ ucfirst($pesanan->status_pesanan) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right">
                                            Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">Belum ada pesanan masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-red-100 flex justify-between items-center bg-red-50/50">
                        <h3 class="font-bold text-red-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Peringatan Stok Tipis
                        </h3>
                    </div>
                    <div class="p-4">
                        <ul class="space-y-3">
                            @forelse($barangStokTipis as $barang)
                                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100 hover:border-red-200 transition">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0 border border-gray-300">
                                            @if($barang->gambar)
                                                <img src="{{ asset('storage/'.$barang->gambar) }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-gray-400 text-xs">IMG</div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $barang->nama_barang }}</p>
                                            <p class="text-[10px] text-gray-500 uppercase">{{ $barang->kategori->nama_kategori ?? 'Umum' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black {{ $barang->stok == 0 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                            Sisa {{ $barang->stok }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <div class="py-8 text-center">
                                    <div class="inline-block p-3 bg-green-100 text-green-600 rounded-full mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">Semua stok barang aman.</p>
                                </div>
                            @endforelse
                        </ul>
                        
                        @if($barangStokTipisCount > 5)
                            <a href="{{ route('admin.barang.index') }}" class="block text-center mt-4 text-sm text-red-600 font-semibold hover:underline">
                                Lihat {{ $barangStokTipisCount - 5 }} barang lainnya &rarr;
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>