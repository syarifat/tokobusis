<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Pengajuan Event Pelanggan') }}
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
                    <h3 class="text-lg font-bold mb-6">Daftar Permintaan Event (Bon/Cicilan)</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Pengajuan</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Pelanggan</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Detail Acara</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            {{ $event->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="font-bold text-gray-900">{{ $event->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $event->user->no_hp }}</p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="font-bold text-indigo-600">{{ $event->nama_acara }}</p>
                                            <p class="text-xs text-gray-600">Pelaksanaan: {{ $event->tanggal_acara->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500 italic mt-1">"{{ $event->keterangan ?? 'Tanpa keterangan' }}"</p>
                                        </td>
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
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                            @if($event->status == 'menunggu')
                                                <div class="flex justify-center gap-2">
                                                    <form action="{{ route('admin.event.updateStatus', $event->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="status" value="disetujui">
                                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-1 px-2 rounded" onclick="return confirm('Setujui event ini? Pelanggan akan bisa menggunakan fitur Bon.');">Setujui</button>
                                                    </form>
                                                    <form action="{{ route('admin.event.updateStatus', $event->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="status" value="ditolak">
                                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded" onclick="return confirm('Tolak event ini?');">Tolak</button>
                                                    </form>
                                                </div>
                                            @elseif($event->status == 'disetujui')
                                                <form action="{{ route('admin.event.updateStatus', $event->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="selesai">
                                                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white text-xs font-bold py-1 px-2 rounded" onclick="return confirm('Tandai event ini sebagai Selesai/Tutup?');">Tandai Selesai</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">Belum ada pengajuan event dari pelanggan.</td>
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