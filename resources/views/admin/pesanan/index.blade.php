<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Pesanan Toko</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <form action="{{ route('admin.pesanan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Pesanan</label>
                            <select name="jenis" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Semua Jenis</option>
                                <option value="reguler" {{ request('jenis') == 'reguler' ? 'selected' : '' }}>Reguler</option>
                                <option value="event" {{ request('jenis') == 'event' ? 'selected' : '' }}>Event (Bon)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Bayar</label>
                            <select name="status_bayar" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="belum_bayar" {{ request('status_bayar') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="cicilan" {{ request('status_bayar') == 'cicilan' ? 'selected' : '' }}>Cicilan</option>
                                <option value="lunas" {{ request('status_bayar') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition text-sm">
                                Filter
                            </button>
                            @if(request()->has('jenis') || request()->has('status_bayar') || request()->has('start_date'))
                                <a href="{{ route('admin.pesanan.index') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-md text-center transition text-sm flex items-center justify-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Pelanggan</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Jenis</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Tanggal</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Detail Pesanan</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Status Bayar</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Status Pesanan</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanans as $p)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                    <p class="font-bold text-gray-900">{{ $p->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $p->user->no_hp }}</p>
                                </td>
                                
                                <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                    <span class="px-2 py-1 rounded text-white text-[10px] font-bold {{ $p->jenis_pesanan == 'event' ? 'bg-purple-500' : 'bg-blue-500' }}">
                                        {{ strtoupper($p->jenis_pesanan) }}
                                    </span>
                                    @if($p->nama_event)
                                        <p class="text-[9px] text-gray-500 mt-1 uppercase truncate w-20 mx-auto" title="{{ $p->nama_event }}">{{ $p->nama_event }}</p>
                                    @endif
                                </td>
                                
                                <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                    <p class="font-bold text-gray-700">{{ $p->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $p->created_at->format('H:i') }} WIB</p>
                                </td>

                                <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                    <p class="font-mono text-indigo-600 font-bold">{{ $p->kode_pesanan }}</p>
                                    <p class="text-xs font-semibold text-gray-600">Total: Rp {{ number_format($p->total_harga, 0, ',', '.') }}</p>
                                    <p class="text-xs italic bg-gray-100 px-1 inline-block mt-1 rounded">Tgl Antar: {{ \Carbon\Carbon::parse($p->tanggal_pengantaran)->format('d/m/Y') }}</p>
                                </td>

                                <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                    @if($p->status_pembayaran == 'lunas')
                                        <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-bold uppercase">Lunas</span>
                                    @elseif($p->status_pembayaran == 'cicilan')
                                        <div class="flex flex-col items-center">
                                            <span class="bg-orange-100 text-orange-800 py-1 px-3 rounded-full text-xs font-bold uppercase">Cicilan</span>
                                            <p class="text-[10px] mt-1 text-gray-500 font-bold">Dibayar: Rp {{ number_format($p->total_dibayar, 0, ',', '.') }}</p>
                                        </div>
                                    @else
                                        <span class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-xs font-bold uppercase whitespace-nowrap">Belum Bayar</span>
                                    @endif
                                </td>

                                <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                    <form action="{{ route('admin.pesanan.updateStatus', $p->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded border-gray-300 focus:ring-indigo-500 cursor-pointer w-28 text-center font-bold">
                                            <option value="menunggu" {{ $p->status_pesanan == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="proses" {{ $p->status_pesanan == 'proses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="dikirim" {{ $p->status_pesanan == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                            <option value="selesai" {{ $p->status_pesanan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="dibatalkan" {{ $p->status_pesanan == 'dibatalkan' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                    </form>
                                </td>

                                
                                <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                    <a href="{{ route('admin.pesanan.show', $p->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-md font-bold text-xs transition-colors duration-200 border border-indigo-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-gray-500 border-b border-gray-200">Tidak ada pesanan yang sesuai dengan filter.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>