<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('pelanggan.checkout.process') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6"
                  x-data="{ 
                      jenis: 'reguler', 
                      pengiriman: 'diantar', 
                      pembayaran: 'transfer',
                      subtotal: {{ $subtotal }},
                      ongkirDasar: {{ $ongkir }},
                      batasGratis: {{ $batasGratisOngkir }},
                      latitude: '',
                      longitude: '',
                      locating: false,
                      locationSuccess: false,
                      locationError: '',
                      get ongkirAktif() {
                          return this.pengiriman === 'ambil_sendiri' ? 0 : this.ongkirDasar;
                      },
                      get grandTotal() {
                          return this.subtotal + this.ongkirAktif;
                      },
                      getLocation() {
                          this.locating = true;
                          this.locationError = '';
                          if (navigator.geolocation) {
                              navigator.geolocation.getCurrentPosition(
                                  (position) => {
                                      this.latitude = position.coords.latitude;
                                      this.longitude = position.coords.longitude;
                                      this.locating = false;
                                      this.locationSuccess = true;
                                  },
                                  (error) => {
                                      this.locating = false;
                                      this.locationSuccess = false;
                                      this.locationError = 'Gagal mengambil lokasi. Izinkan akses GPS di browser Anda.';
                                  },
                                  { enableHighAccuracy: true }
                              );
                          } else {
                              this.locating = false;
                              this.locationError = 'Browser tidak mendukung fitur lokasi.';
                          }
                      },
                      init() {
                          // Otomatis minta lokasi saat halaman dimuat jika defaultnya 'diantar'
                          if(this.pengiriman === 'diantar') this.getLocation();
                          
                          // Pantau perubahan: jika klik 'diantar' dan belum ada koordinat, cari lokasi
                          this.$watch('pengiriman', value => {
                              if(value === 'diantar' && !this.latitude) {
                                  this.getLocation();
                              }
                          });
                      }
                  }">
                
                @csrf
                
                @foreach($keranjangs as $item)
                    <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                @endforeach

                <input type="hidden" name="latitude" x-model="latitude">
                <input type="hidden" name="longitude" x-model="longitude">

                <div class="md:col-span-2 space-y-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 border-b border-gray-200">
                            
                            <h3 class="text-base font-bold mb-3 flex items-center text-gray-800">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                1. Pilih Metode Pengiriman
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                <label class="flex items-start p-4 border rounded-lg cursor-pointer transition" :class="pengiriman == 'diantar' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:bg-gray-50'">
                                    <input type="radio" name="metode_pengiriman" value="diantar" x-model="pengiriman" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-900">Diantar Kurir Toko</span>
                                        <span class="block text-xs text-gray-500 mt-1">Sesuai antrean pengiriman toko. Kena tarif ongkir flat.</span>
                                    </div>
                                </label>
                                <label class="flex items-start p-4 border rounded-lg cursor-pointer transition" :class="pengiriman == 'ambil_sendiri' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:bg-gray-50'">
                                    <input type="radio" name="metode_pengiriman" value="ambil_sendiri" x-model="pengiriman" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-900">Ambil Sendiri ke Toko</span>
                                        <span class="block text-xs text-green-600 font-bold mt-1">Gratis Ongkir!</span>
                                    </div>
                                </label>
                            </div>

                            <h3 class="text-base font-bold mb-3 flex items-center text-gray-800 border-t pt-6">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                2. Pilih Metode Pembayaran
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                <label class="flex items-start p-4 border rounded-lg cursor-pointer transition" :class="pembayaran == 'transfer' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:bg-gray-50'">
                                    <input type="radio" name="tipe_pembayaran" value="transfer" x-model="pembayaran" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-900">Transfer / QRIS (Non-Tunai)</span>
                                        <span class="block text-xs text-gray-500 mt-1">Aman, cepat, diproses otomatis via Midtrans.</span>
                                    </div>
                                </label>
                                <label class="flex items-start p-4 border rounded-lg cursor-pointer transition" :class="pembayaran == 'cash' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:bg-gray-50'">
                                    <input type="radio" name="tipe_pembayaran" value="cash" x-model="pembayaran" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-900" x-text="pengiriman == 'diantar' ? 'Bayar di Tempat (COD)' : 'Bayar di Kasir (Tunai)'"></span>
                                        <span class="block text-xs text-gray-500 mt-1">Bayar dengan uang pas kepada Bu Sis / Kurir.</span>
                                    </div>
                                </label>
                            </div>

                            <h3 class="text-base font-bold mb-3 flex items-center text-gray-800 border-t pt-6">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                3. Jenis Transaksi
                            </h3>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border rounded cursor-pointer hover:bg-gray-50 transition" :class="jenis == 'reguler' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'">
                                    <input type="radio" name="jenis_pesanan" value="reguler" x-model="jenis" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-900">Pesanan Reguler Biasa</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">Transaksi umum harian.</span>
                                    </div>
                                </label>

                                @if($eventsDisetujui->count() > 0)
                                    <label class="flex items-center p-4 border rounded cursor-pointer hover:bg-gray-50 transition" :class="jenis == 'event' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'">
                                        <input type="radio" name="jenis_pesanan" value="event" x-model="jenis" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                        <div class="ml-3">
                                            <span class="block text-sm font-bold text-gray-900">Gunakan Bon / Cicilan (Khusus Event)</span>
                                            <span class="block text-xs text-gray-500 mt-0.5">Bayar bertahap setelah acara selesai.</span>
                                        </div>
                                    </label>

                                    <div x-show="jenis == 'event'" class="mt-2 p-4 bg-yellow-50 rounded-lg border border-yellow-200 flex items-start gap-3">
                                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        <div class="w-full">
                                            @php $activeEvent = $eventsDisetujui->first(); @endphp
                                            <p class="font-bold text-yellow-900 text-sm">Target Bon: {{ $activeEvent->nama_acara }}</p>
                                            <input type="hidden" name="event_id" value="{{ $activeEvent->id }}">
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 border-b border-gray-200">
                            <h3 class="text-base font-bold mb-4 flex items-center text-gray-800">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                4. Detail Alamat & Waktu
                            </h3>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pemesan</label>
                                <input type="text" class="bg-gray-100 border-gray-300 rounded w-full py-2 px-3 text-gray-700 cursor-not-allowed" value="{{ Auth::user()->name }}" readonly>
                            </div>
                            
                            <div class="mb-4" x-show="pengiriman == 'diantar'">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Pengiriman</label>
                                <textarea class="bg-gray-100 border-gray-300 rounded w-full py-2 px-3 text-gray-700 cursor-not-allowed" rows="2" readonly>{{ Auth::user()->alamat }}</textarea>
                                
                                <div class="mt-2 p-3 bg-blue-50 border border-blue-100 rounded text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-blue-800">Pin Titik Lokasi Antar (Maps)</span>
                                        <button type="button" @click="getLocation()" class="text-blue-600 underline hover:text-blue-800 focus:outline-none">Deteksi Ulang</button>
                                    </div>
                                    <div x-show="locating" class="text-blue-600 mt-1 font-semibold flex items-center">
                                        <svg class="animate-spin h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sedang mendeteksi...
                                    </div>
                                    <div x-show="locationSuccess" class="text-green-600 font-bold mt-1">
                                        ✅ Titik lokasi berhasil diamankan.
                                    </div>
                                    <div x-show="locationError" class="text-red-600 font-bold mt-1" x-text="locationError"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" x-text="pengiriman == 'diantar' ? 'Tanggal Pengantaran Barang' : 'Tanggal Pengambilan ke Toko'"></label>
                                <input type="date" name="tanggal_pengantaran" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Pesanan (Opsional)</label>
                                <textarea name="catatan" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" placeholder="Contoh: Tolong dikemas pakai kardus ya bu..."></textarea>
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
                                            <p class="text-gray-500">{{ $item->qty }} {{ $item->barang->satuan }} x Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</p>
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
                                    <span class="font-bold" :class="ongkirAktif == 0 ? 'text-green-600' : 'text-gray-900'">
                                        <span x-show="ongkirAktif == 0">Gratis!</span>
                                        <span x-show="ongkirAktif > 0">Rp <span x-text="new Intl.NumberFormat('id-ID').format(ongkirAktif)"></span></span>
                                    </span>
                                </div>
                            </div>

                            <div x-show="pengiriman == 'diantar' && ongkirAktif > 0" class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <div class="text-xs text-blue-800">
                                    Tambah belanjaan <b>Rp <span x-text="new Intl.NumberFormat('id-ID').format(batasGratis - subtotal)"></span></b> lagi untuk mendapatkan <b>Gratis Ongkir!</b>
                                </div>
                            </div>
                            <div x-show="pengiriman == 'diantar' && ongkirAktif == 0" class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3 flex items-center gap-2 text-xs text-green-800 font-bold">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Selamat! Kamu mendapatkan Gratis Ongkir.
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                                    <span class="text-xl font-black text-indigo-600">Rp <span x-text="new Intl.NumberFormat('id-ID').format(grandTotal)"></span></span>
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