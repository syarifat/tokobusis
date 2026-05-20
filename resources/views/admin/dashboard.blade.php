<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Dashboard Admin') }}
            </h2>
            
            {{-- Filter Form --}}
            <form action="{{ route('admin.dashboard') }}" method="GET" class="bg-white p-2 rounded-xl border border-gray-200 shadow-sm flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="text-xs border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-gray-400 font-bold">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="text-xs border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <select name="period" class="text-xs border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition">
                    Filter
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Main Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-10">
                        <svg class="w-24 h-24 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="p-4 bg-green-100 rounded-xl text-green-600 mr-4 z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="z-10">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pendapatan Lunas</p>
                        <h4 class="text-2xl font-black text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center relative overflow-hidden">
                    <div class="p-4 bg-blue-100 rounded-xl text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pesanan Aktif</p>
                        <h4 class="text-2xl font-black text-gray-900">{{ $pesananMenunggu }} <span class="text-sm font-normal text-gray-500">Antrean</span></h4>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center relative overflow-hidden">
                    <div class="p-4 bg-purple-100 rounded-xl text-purple-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Pelanggan</p>
                        <h4 class="text-2xl font-black text-gray-900">{{ $totalPelanggan }} <span class="text-sm font-normal text-gray-500">Orang</span></h4>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center relative overflow-hidden">
                    <div class="p-4 bg-red-100 rounded-xl text-red-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Stok Menipis</p>
                        <h4 class="text-2xl font-black text-red-600">{{ $barangStokTipisCount }} <span class="text-sm font-normal text-red-400">Barang</span></h4>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <div class="w-2 h-6 bg-blue-600 rounded-full mr-2"></div>
                        Grafik Pendapatan Murni (Pokok)
                    </h3>
                    <div class="h-64">
                        <canvas id="pureRevenueChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <div class="w-2 h-6 bg-orange-500 rounded-full mr-2"></div>
                        Daftar Cicilan Berlangsung
                    </h3>
                    <div class="flex-1 overflow-y-auto" style="max-height: 256px;">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 text-left text-[10px] font-black text-gray-500 uppercase">Pelanggan/Event</th>
                                    <th class="px-4 py-2 text-center text-[10px] font-black text-gray-500 uppercase">Progres</th>
                                    <th class="px-4 py-2 text-right text-[10px] font-black text-gray-500 uppercase">Total/Sisa</th>
                                    <th class="px-4 py-2 text-center text-[10px] font-black text-gray-500 uppercase">Jatuh Tempo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($ongoingInstallments as $ci)
                                    @php
                                        $x_paid = 0;
                                        if($ci->jumlah_cicilan > 0) {
                                            $nom_cicil = $ci->total_harga / $ci->jumlah_cicilan;
                                            $x_paid = floor($ci->total_dibayar / max(1, $nom_cicil));
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3">
                                            <p class="text-xs font-bold text-gray-800">{{ $ci->user->name }}</p>
                                            <p class="text-[9px] text-gray-400 uppercase font-bold">{{ $ci->nama_event ?? 'Reguler' }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-[9px] font-black">
                                                {{ $x_paid }}/{{ $ci->jumlah_cicilan }}x
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <p class="text-[10px] font-bold text-gray-800">Rp {{ number_format($ci->total_harga, 0, ',', '.') }}</p>
                                            <p class="text-[9px] text-red-500">Sisa: {{ number_format($ci->sisa_tagihan, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @php 
                                                $deadline = \Carbon\Carbon::parse($ci->tenggat_pembayaran);
                                                $isLate = $deadline->isPast() && !$ci->is_lunas;
                                            @endphp
                                            <span class="text-[9px] font-bold {{ $isLate ? 'text-red-600 bg-red-50' : 'text-gray-500 bg-gray-50' }} px-2 py-1 rounded">
                                                {{ $deadline->format('d/m/y') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-10 text-center text-xs text-gray-400">Tidak ada cicilan aktif.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Customer Statistics Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Sering Beli --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800">Top Customers (Belanja)</h3>
                    </div>
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pelanggan</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Total Pesanan</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Total Belanja</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($topBuyers as $buyer)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $buyer->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $buyer->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-black">
                                                {{ $buyer->total_pesanan }}x
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-green-600">
                                            Rp {{ number_format($buyer->total_spent, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="p-6 text-center text-gray-500">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Sering Bon/Event --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 text-orange-700">Pelanggan Sering Bon (Event)</h3>
                    </div>
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pelanggan</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Jumlah Bon</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($topEventCustomers as $cust)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $cust->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $cust->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-black">
                                                {{ $cust->total_event }}x Event
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <a href="{{ route('admin.pelanggan.index') }}?search={{ $cust->user->name }}" class="text-xs font-bold text-indigo-600 hover:underline">Profil</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="p-6 text-center text-gray-500">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Existing Recent Orders & Low Stock --}}
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
                                    <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">Belum ada pesanan masuk.</td></tr>
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const revenueLabels = @json($revenueData->pluck('label'));
        const pureRevenueData = @json($revenueData->pluck('pure_revenue'));

        const chartConfig = (id, label, labels, data, color, isCurrency = true) => {
            return new Chart(document.getElementById(id), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: color + 'CC', // 80% opacity
                        borderColor: color,
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (isCurrency) return 'Rp ' + value.toLocaleString();
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
        };

        chartConfig('pureRevenueChart', 'Pendapatan Murni', revenueLabels, pureRevenueData, '#2563eb', true);
    </script>
    @endpush
</x-app-layout>