<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="nama_kategori" class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="nama_kategori" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nama_kategori') border-red-500 @enderror" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                            @error('nama_kategori')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
                            <a href="{{ route('admin.kategori.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>