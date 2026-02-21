<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Event / Acara (Sistem Bon)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold mb-4 text-indigo-700">Form Pengajuan Acara Baru</h3>
                    <p class="text-sm text-gray-600 mb-6">Ajukan acara kamu di sini untuk mendapatkan akses fitur pembayaran tempo (Bon/Cicilan). Pengajuan harus disetujui oleh Admin terlebih dahulu.</p>
                    
                    <form action="{{ route('pelanggan.event.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Acara</label>
                                <input type="text" name="nama_acara" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: Pernikahan Anak Pertama, Pitonan" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pelaksanaan Acara</label>
                                <input type="date" name="tanggal_acara" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan Tambahan (Opsional)</label>
                                <textarea name="keterangan" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Tuliskan detail singkat atau estimasi kebutuhan barang..."></textarea>
                            </div>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">
                            Ajukan Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Riwayat Pengajuan Acara Saya</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Acara</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Acara</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-medium">{{ $event->nama_acara }}</td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $event->tanggal_acara->format('d M Y') }}</td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $event->keterangan ?? '-' }}</td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                            @if($event->status == 'menunggu')
                                                <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full text-xs font-semibold">Menunggu</span>
                                            @elseif($event->status == 'disetujui')
                                                <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Disetujui</span>
                                            @elseif($event->status == 'ditolak')
                                                <span class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-xs font-semibold">Ditolak</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 py-1 px-3 rounded-full text-xs font-semibold">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">Belum ada riwayat pengajuan acara.</td>
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