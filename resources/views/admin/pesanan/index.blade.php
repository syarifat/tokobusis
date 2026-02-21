<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Pesanan Toko</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Pelanggan</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Detail Pesanan</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase">Status Pesanan</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanans as $p)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <p class="font-bold">{{ $p->user->name }}</p>
                                <p class="text-xs">{{ $p->user->no_hp }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <p class="font-mono text-indigo-600 font-bold">{{ $p->kode_pesanan }}</p>
                                <p class="text-xs font-semibold text-gray-600">Total: Rp {{ number_format($p->total_harga, 0, ',', '.') }}</p>
                                <p class="text-xs italic bg-gray-100 px-1 inline-block">Tgl Antar: {{ $p->tanggal_pengantaran }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                <form action="{{ route('admin.pesanan.updateStatus', $p->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="text-xs rounded border-gray-300">
                                        <option value="menunggu" {{ $p->status_pesanan == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="proses" {{ $p->status_pesanan == 'proses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="dikirim" {{ $p->status_pesanan == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="selesai" {{ $p->status_pesanan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ $p->status_pesanan == 'dibatalkan' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                <span class="px-2 py-1 rounded text-white text-xs {{ $p->jenis_pesanan == 'event' ? 'bg-purple-500' : 'bg-blue-500' }}">
                                    {{ strtoupper($p->jenis_pesanan) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>