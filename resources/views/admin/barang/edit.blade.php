<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                                <input type="text" name="nama_barang" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                                <select name="kategori_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ (old('kategori_id', $barang->kategori_id) == $kategori->id) ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                                <input type="number" name="harga" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="{{ old('harga', $barang->harga) }}" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Stok</label>
                                    <input type="number" name="stok" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="{{ old('stok', $barang->stok) }}" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                                    <input type="text" name="satuan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="{{ old('satuan', $barang->satuan) }}" required>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Gambar Produk (Biarkan kosong jika tidak ingin mengubah)</label>
                                @if($barang->gambar)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $barang->gambar) }}" class="w-32 h-32 object-cover rounded shadow">
                                    </div>
                                @endif
                                <input type="file" name="gambar" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                                <textarea name="deskripsi" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Update Produk</button>
                            <a href="{{ route('admin.barang.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>