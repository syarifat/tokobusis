<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Pelanggan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Daftar Pelanggan Terdaftar</h3>
                    
                    <form action="{{ route('admin.pelanggan.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, no hp..." class="text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-bold">Cari</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Nama & Email</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">No. WhatsApp</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Alamat Lengkap</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pelanggans as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-5 text-sm">
                                    <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-5 text-sm">
                                    <span class="text-blue-600 font-medium">{{ $user->no_hp ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-5 text-sm text-gray-600 max-w-xs truncate">
                                    {{ $user->alamat ?? 'Belum diisi' }}
                                </td>
                                <td class="px-5 py-5 text-sm text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.pelanggan.edit', $user->id) }}" class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1 rounded text-xs font-bold transition">Edit</a>
                                        <form action="{{ route('admin.pelanggan.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini? Semua data pesanan yang terkait mungkin akan terpengaruh.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded text-xs font-bold transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-gray-500">Belum ada pelanggan yang terdaftar atau ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200">
                    {{ $pelanggans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>