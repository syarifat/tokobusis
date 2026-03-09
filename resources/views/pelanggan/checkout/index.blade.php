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

                                    <div x-show="jenis == 'event'" class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200 flex items-start gap-3">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="w-full">
                                            <h4 class="text-sm font-bold text-yellow-800">Event Aktif Terdeteksi</h4>
                                            
                                            @php $activeEvent = $eventsDisetujui->first(); @endphp
                                            
                                            <div class="mt-2 p-3 bg-white border border-yellow-100 rounded shadow-sm">
                                                <p class="font-bold text-gray-900">{{ $activeEvent->nama_acara }}</p>
                                                <p class="text-xs text-gray-500 mt-1">Jadwal: {{ \Carbon\Carbon::parse($activeEvent->tanggal_acara)->format('d F Y') }}</p>
                                                <input type="hidden" name="event_id" value="{{ $activeEvent->id }}">
                                            </div>
                                            
                                            <p class="text-xs text-yellow-700 mt-2 italic">
                                                *Tagihan untuk pesanan ini akan masuk ke sistem Bon dan harus dilunasi maksimal H+7 setelah acara selesai.
                                            </p>
                                        </div>
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
                            
                            <div class="divide-y divide-gray-200 max-h-60 overflow-y-auto mb-4 pr-2">
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

                            <div class="border-t border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Subtotal Produk</span>
                                    <span class="font-bold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Ongkos Kirim</span>
                                    @if($ongkir == 0)
                                        <span class="font-bold text-green-600">Gratis!</span>
                                    @else
                                        <span class="font-bold text-gray-900">Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>

                            @if($ongkir > 0)
                                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <div class="text-xs text-blue-800">
                                        Tambah belanjaan <b>Rp {{ number_format($batasGratisOngkir - $subtotal, 0, ',', '.') }}</b> lagi untuk mendapatkan <b>Gratis Ongkir!</b>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3 flex items-center gap-2 text-xs text-green-800 font-bold">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Selamat! Kamu mendapatkan Gratis Ongkos Kirim.
                                </div>
                            @endif

                            <div class="border-t border-gray-200 pt-4 mt-4 mb-6">
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