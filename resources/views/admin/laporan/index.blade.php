<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Penjualan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Periode: {{ $title }}</h3>
                    <p class="text-sm text-gray-500">Ringkasan transaksi dan pendapatan toko.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <a href="{{ route('admin.laporan.pdf', ['filter' => $filter]) }}" target="_blank" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path></svg>
                        Cetak PDF
                    </a>

                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <a href="{{ route('admin.laporan.index', ['filter' => 'hari']) }}" class="px-4 py-2 rounded-md text-sm font-bold transition {{ $filter == 'hari' ? 'bg-white text-indigo-600 shadow' : 'text-gray-500 hover:text-gray-900' }}">Hari Ini</a>
                        <a href="{{ route('admin.laporan.index', ['filter' => 'minggu']) }}" class="px-4 py-2 rounded-md text-sm font-bold transition {{ $filter == 'minggu' ? 'bg-white text-indigo-600 shadow' : 'text-gray-500 hover:text-gray-900' }}">Minggu Ini</a>
                        <a href="{{ route('admin.laporan.index', ['filter' => 'bulan']) }}" class="px-4 py-2 rounded-md text-sm font-bold transition {{ $filter == 'bulan' ? 'bg-white text-indigo-600 shadow' : 'text-gray-500 hover:text-gray-900' }}">Bulan Ini</a>
                        <a href="{{ route('admin.laporan.index', ['filter' => 'tahun']) }}" class="px-4 py-2 rounded-md text-sm font-bold transition {{ $filter == 'tahun' ? 'bg-white text-indigo-600 shadow' : 'text-gray-500 hover:text-gray-900' }}">Tahun Ini</a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-indigo-600 p-6 rounded-2xl shadow-sm text-white">
                    <p class="text-indigo-200 text-sm font-bold mb-1">Total Omzet (Nilai Pesanan)</p>
                    <h4 class="text-3xl font-black">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h4>
                </div>
                
                <div class="bg-green-500 p-6 rounded-2xl shadow-sm text-white">
                    <p class="text-green-100 text-sm font-bold mb-1">Pendapatan Masuk (Tunai)</p>
                    <h4 class="text-3xl font-black">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                </div>

                <div class="bg-orange-500 p-6 rounded-2xl shadow-sm text-white">
                    <p class="text-orange-100 text-sm font-bold mb-1">Piutang / Belum Dibayar</p>
                    <h4 class="text-3xl font-black">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h4>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center">
                    <p class="text-gray-500 text-sm font-bold mb-1">Jumlah Transaksi Sah</p>
                    <h4 class="text-4xl font-black text-gray-900">{{ $totalTransaksi }}</h4>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900">Rincian Transaksi ({{ $title }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Kode & Pelanggan</th>
                                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase">Nilai Pesanan</th>
                                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase">Telah Dibayar</th>
                                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pesanans as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $p->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.pesanan.show', $p->id) }}" class="font-mono font-bold text-indigo-600 hover:underline">{{ $p->kode_pesanan }}</a>
                                    <p class="text-gray-500 text-xs mt-1">{{ $p->user->name }} ({{ strtoupper($p->jenis_pesanan) }})</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">
                                    Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $p->total_dibayar < $p->total_harga ? 'text-orange-500' : 'text-green-600' }}">
                                    Rp {{ number_format($p->total_dibayar, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $p->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-700' : ($p->status_pembayaran == 'cicilan' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                                        {{ str_replace('_', ' ', $p->status_pembayaran) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    Tidak ada transaksi pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>