<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Produk Toko Bu Sis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form action="{{ route('pelanggan.dashboard') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    </div>
                    <div class="w-full md:w-1/3">
                        <select name="kategori" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md">
                            Cari / Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($barangs as $barang)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col justify-between">
                        <div class="h-48 bg-gray-200 w-full">
                            @if($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">Tanpa Gambar</div>
                            @endif
                        </div>
                        
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="text-xs text-indigo-600 font-semibold uppercase tracking-wider mb-1">
                                {{ $barang->kategori->nama_kategori ?? 'Umum' }}
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $barang->nama_barang }}</h3>
                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $barang->deskripsi }}</p>
                            
                            <div class="mt-auto">
                                <div class="text-xl font-bold text-gray-900 mb-2">Rp {{ number_format($barang->harga, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500"> / {{ $barang->satuan }}</span></div>
                                <div class="text-sm text-gray-500 mb-4">Sisa Stok: <span class="font-semibold {{ $barang->stok > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $barang->stok }}</span></div>
                                
                                @if($barang->stok > 0)
                                    <form action="{{ route('pelanggan.keranjang.store') }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                                        <input type="number" name="qty" value="1" min="1" max="{{ $barang->stok }}" class="w-20 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center" required>
                                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                            + Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full bg-gray-400 text-white font-semibold py-2 px-4 rounded-md cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 rounded-lg shadow-sm text-center">
                        <p class="text-gray-500 text-lg">Belum ada produk yang tersedia atau ditemukan.</p>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>