<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout Pesanan') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('pelanggan.checkout.process') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6"
                  x-data="{ 
                      jenis: 'reguler', 
                      pengiriman: 'diantar', 
                      pembayaran: 'transfer',
                      cicilan: 1,
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
                      get cicilanPerBulan() {
                          return this.grandTotal / this.cicilan;
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
                          // 1. Setup Peta
                          let defaultLat = -7.0628; 
                          let defaultLng = 112.4301;
                          
                          const map = L.map('map').setView([defaultLat, defaultLng], 13);
                          
                          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                              attribution: '&copy; OpenStreetMap'
                          }).addTo(map);

                          // 2. Setup Pin/Marker
                          const marker = L.marker([defaultLat, defaultLng], {
                              draggable: true
                          }).addTo(map);

                          // 3. Update Alpine State saat marker digeser manual
                          marker.on('dragend', (e) => {
                              let pos = e.target.getLatLng();
                              this.latitude = pos.lat;
                              this.longitude = pos.lng;
                              this.locationSuccess = true;
                          });

                          // 4. Update Alpine State saat peta di-klik
                          map.on('click', (e) => {
                              marker.setLatLng(e.latlng);
                              this.latitude = e.latlng.lat;
                              this.longitude = e.latlng.lng;
                              this.locationSuccess = true;
                          });

                          // 5. Sinkronkan jika 'latitude' berubah (misal karena tombol GPS diklik)
                          this.$watch('latitude', (val) => {
                              if(val) {
                                  let newPos = new L.LatLng(this.latitude, this.longitude);
                                  marker.setLatLng(newPos);
                                  map.setView(newPos, 16); // Auto-zoom mendekat
                                  
                                  // Workaround untuk bug Leaflet di dalam elemen x-show yang disembunyikan
                                  setTimeout(function(){ map.invalidateSize()}, 400);
                              }
                          });

                          // Otomatis minta lokasi saat halaman dimuat jika defaultnya 'diantar'
                          if(this.pengiriman === 'diantar') {
                              this.getLocation();
                          }
                          
                          // Pantau perubahan mode pengiriman
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

                                    <div x-show="jenis == 'event'" class="mt-2 p-4 bg-yellow-50 rounded-lg border border-yellow-200 flex flex-col gap-3">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            <div class="w-full">
                                                @php $activeEvent = $eventsDisetujui->first(); @endphp
                                                <p class="font-bold text-yellow-900 text-sm">Target Bon: {{ $activeEvent->nama_acara }}</p>
                                                <p class="text-xs text-yellow-700 mt-1">Tenggat pembayaran maksimal 1 bulan setelah tanggal acara ({{ \Carbon\Carbon::parse($activeEvent->tanggal_acara)->addMonths(1)->format('d M Y') }}).</p>
                                                <input type="hidden" name="event_id" value="{{ $activeEvent->id }}">
                                            </div>
                                        </div>
                                        <div class="mt-2 pt-3 border-t border-yellow-200">
                                            <label class="block text-yellow-900 text-sm font-bold mb-2">Pilih Durasi Cicilan</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <label class="text-center p-2 border rounded-lg cursor-pointer transition" :class="cicilan == 1 ? 'border-yellow-500 bg-yellow-100 font-bold' : 'border-yellow-300 bg-white hover:bg-yellow-50'">
                                                    <input type="radio" name="jumlah_cicilan" value="1" x-model.number="cicilan" class="hidden">
                                                    <span class="block text-sm text-yellow-900">1x (Lunas)</span>
                                                </label>
                                                <label class="text-center p-2 border rounded-lg cursor-pointer transition" :class="cicilan == 3 ? 'border-yellow-500 bg-yellow-100 font-bold' : 'border-yellow-300 bg-white hover:bg-yellow-50'">
                                                    <input type="radio" name="jumlah_cicilan" value="3" x-model.number="cicilan" class="hidden">
                                                    <span class="block text-sm text-yellow-900">3x Cicilan</span>
                                                </label>
                                                <label class="text-center p-2 border rounded-lg cursor-pointer transition" :class="cicilan == 6 ? 'border-yellow-500 bg-yellow-100 font-bold' : 'border-yellow-300 bg-white hover:bg-yellow-50'">
                                                    <input type="radio" name="jumlah_cicilan" value="6" x-model.number="cicilan" class="hidden">
                                                    <span class="block text-sm text-yellow-900">6x Cicilan</span>
                                                </label>
                                            </div>
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
                                <textarea class="bg-gray-100 border-gray-300 rounded w-full py-2 px-3 text-gray-700 cursor-not-allowed mb-3" rows="2" readonly>{{ Auth::user()->alamat }}</textarea>
                                
                                <div class="border rounded-lg overflow-hidden border-gray-200">
                                    <div class="bg-gray-50 px-4 py-2 border-b flex justify-between items-center">
                                        <span class="text-xs font-bold text-gray-600 uppercase">Pastikan Titik Lokasi Anda</span>
                                        <button type="button" @click="getLocation()" class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded border border-indigo-200 hover:bg-indigo-600 hover:text-white transition">
                                            Gunakan GPS Saya
                                        </button>
                                    </div>

                                    <div x-show="locating" class="bg-blue-100 text-blue-700 p-2 text-xs text-center font-bold flex items-center justify-center">
                                        <svg class="animate-spin h-3 w-3 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> 
                                        Sedang mencari sinyal satelit GPS...
                                    </div>
                                    <div x-show="locationError" class="bg-red-100 text-red-700 p-2 text-xs text-center font-bold" x-text="locationError"></div>

                                    <div id="map" style="height: 300px;" class="z-0" wire:ignore></div>

                                    <div class="p-3 bg-blue-50 text-[10px] text-blue-700 flex justify-between items-center">
                                        <p>💡 <b>Tips:</b> Geser penanda di peta jika titik GPS kurang akurat.</p>
                                        <div x-show="locationSuccess" class="text-green-600 font-bold">✅ Titik Terkunci.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 mt-6">
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
                                <div x-show="jenis == 'event' && cicilan > 1" class="flex justify-between items-center mt-3 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                    <span class="text-sm font-bold text-yellow-800">Rincian per Cicilan</span>
                                    <span class="text-base font-black text-yellow-900">Rp <span x-text="new Intl.NumberFormat('id-ID').format(Math.round(cicilanPerBulan))"></span> <span class="text-xs font-normal">/ cicilan</span></span>
                                </div>
                            </div>

                            <button type="submit" 
                                    class="w-full font-bold py-3 px-4 rounded-lg shadow transition duration-200 flex justify-center items-center"
                                    :class="(pengiriman == 'diantar' && !locationSuccess) ? 'bg-gray-400 text-gray-200 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700 text-white'"
                                    :disabled="pengiriman == 'diantar' && !locationSuccess">
                                <span x-text="(pengiriman == 'diantar' && !locationSuccess) ? 'Menunggu Titik Lokasi...' : 'Buat Pesanan'"></span>
                                <svg x-show="pengiriman == 'ambil_sendiri' || locationSuccess" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
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