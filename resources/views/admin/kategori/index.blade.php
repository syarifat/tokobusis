<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Kategori Sembako</h3>
                        <a href="{{ route('admin.kategori.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Kategori
                        </a>
                    </div>

                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Kategori</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kategoris as $index => $item)
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $index + 1 }}</td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $item->nama_kategori }}</td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm flex space-x-2">
                                        <a href="{{ route('admin.kategori.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">Belum ada data kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>