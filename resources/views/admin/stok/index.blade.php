<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Stok Barang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1 bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
                    <h3 class="font-bold text-gray-900 mb-4 border-b pb-2">Catat Mutasi Stok</h3>
                    <form action="{{ route('admin.stok.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Barang</label>
                            <select name="barang_id" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_barang }} (Sisa: {{ $b->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Mutasi</label>
                                <select name="jenis" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    <option value="masuk">Barang Masuk (+)</option>
                                    <option value="keluar">Barang Keluar (-)</option>
                                    <option value="penyesuaian">Penyesuaian (-)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                <input type="number" name="jumlah" min="1" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                            <textarea name="keterangan" rows="2" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Misal: Restock dari Supplier A atau Barang rusak"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition">
                            Simpan Stok
                        </button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900">Riwayat Pergerakan Stok</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Barang</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Perubahan</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($riwayats as $riwayat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $riwayat->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <p class="font-bold text-gray-900">{{ $riwayat->barang->nama_barang }}</p>
                                        <p class="text-xs text-gray-500 italic">{{ $riwayat->keterangan ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if($riwayat->jenis == 'masuk')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                + {{ $riwayat->jumlah }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                - {{ $riwayat->jumlah }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-black text-gray-700">
                                        {{ $riwayat->stok_sesudah }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">Belum ada riwayat stok.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100">
                        {{ $riwayats->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>