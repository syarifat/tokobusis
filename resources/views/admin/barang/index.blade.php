<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Produk Sembako</h3>
                        <a href="{{ route('admin.barang.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Produk
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gambar</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barangs as $item)
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            @if($item->gambar)
                                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}" class="w-16 h-16 object-cover rounded">
                                            @else
                                                <span class="text-gray-400">No Image</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-medium">{{ $item->nama_barang }}</td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}</td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $item->stok }} {{ $item->satuan }}</td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm flex flex-col space-y-2">
                                            <a href="{{ route('admin.barang.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900 font-semibold">Edit</a>
                                            <form action="{{ route('admin.barang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">Belum ada data barang.</td>
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