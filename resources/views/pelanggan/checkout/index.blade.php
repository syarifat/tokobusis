<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('pelanggan.checkout.process') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @csrf
                
                @foreach($keranjangs as $item)
                    <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                @endforeach

                <div class="md:col-span-2 space-y-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 border-b border-gray-200">
                            <h3 class="text-lg font-bold mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                </svg>
                                Detail Pengiriman
                            </h3>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Penerima</label>
                                <input type="text" class="bg-gray-100 border-gray-300 rounded w-full py-2 px-3 text-gray-700 cursor-not-allowed" value="{{ Auth::user()->name }}" readonly>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Pengiriman</label>
                                <textarea class="bg-gray-100 border-gray-300 rounded w-full py-2 px-3 text-gray-700 cursor-not-allowed" rows="2" readonly>{{ Auth::user()->alamat }}</textarea>
                                <p class="text-xs text-red-500 mt-1">*Ubah alamat melalui pengaturan profil jika tidak sesuai.</p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengantaran Barang</label>
                                <input type="date" name="tanggal_pengantaran" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Pesanan (Opsional)</label>
                                <textarea name="catatan" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" placeholder="Contoh: Tolong dikemas pakai kardus ya bu..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 border-b border-gray-200" x-data="{ jenis: 'reguler' }">
                            <h3 class="text-lg font-bold mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                </svg>
                                Metode Pemesanan & Pembayaran
                            </h3>

                            <div class="space-y-4">
                                <label class="flex items-center p-4 border rounded cursor-pointer hover:bg-gray-50 transition" :class="jenis == 'reguler' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'">
                                    <input type="radio" name="jenis_pesanan" value="reguler" x-model="jenis" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-900">Pesanan Reguler (Bayar Sekarang)</span>
                                        <span class="block text-sm text-gray-500">Bayar menggunakan transfer bank, QRIS, dll melalui Midtrans.</span>
                                    </div>
                                </label>

                                @if($eventsDisetujui->count() > 0)
                                    <label class="flex items-center p-4 border rounded cursor-pointer hover:bg-gray-50 transition" :class="jenis == 'event' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'">
                                        <input type="radio" name="jenis_pesanan" value="event" x-model="jenis" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                        <div class="ml-3">
                                            <span class="block text-sm font-bold text-gray-900">Gunakan Bon / Cicilan (Khusus Event)</span>
                                            <span class="block text-sm text-gray-500">Pesan sekarang, bayar bertahap hingga H+7 setelah acara selesai.</span>
                                        </div>
                                    </label>

                                    <div x-show="jenis == 'event'" class="mt-4 p-4 bg-yellow-50 rounded border border-yellow-200">
                                        <label class="block text-yellow-800 text-sm font-bold mb-2">Pilih Event Anda:</label>
                                        <select name="event_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">-- Pilih Acara --</option>
                                            @foreach($eventsDisetujui as $event)
                                                <option value="{{ $event->id }}">{{ $event->nama_acara }} ({{ \Carbon\Carbon::parse($event->tanggal_acara)->format('d M Y') }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="p-4 bg-gray-50 border border-gray-200 rounded text-sm text-gray-500">
                                        <span class="font-bold">Info:</span> Anda tidak memiliki event/acara yang disetujui Admin untuk menggunakan fitur Bon.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6 text-gray-900 border-b border-gray-200">
                            <h3 class="text-lg font-bold mb-4">Ringkasan Belanja</h3>
                            
                            <div class="divide-y divide-gray-200 max-h-60 overflow-y-auto mb-4">
                                @foreach($keranjangs as $item)
                                    <div class="py-3 flex justify-between text-sm">
                                        <div class="flex-1 pr-4">
                                            <p class="font-medium text-gray-900 line-clamp-1">{{ $item->barang->nama_barang }}</p>
                                            <p class="text-gray-500">{{ $item->qty }} x Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="font-bold text-gray-900">Rp {{ number_format($item->qty * $item->barang->harga, 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 pt-4 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                                    <span class="text-xl font-black text-indigo-600">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow transition duration-200 flex justify-center items-center">
                                Buat Pesanan
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>