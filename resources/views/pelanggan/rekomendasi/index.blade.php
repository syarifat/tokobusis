<x-app-layout>
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900">Rekomendasi Bahan Pintar</h2>
            <p class="text-gray-500 mt-2">Masukkan daftar bahan yang Anda butuhkan, kami carikan opsi kualitas standar & premium untuk Anda.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-10 opacity-5 pointer-events-none">
                <svg class="w-32 h-32 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
            </div>
            
            <form action="{{ route('pelanggan.rekomendasi.index') }}" method="GET" class="relative z-10">
                <label for="bahan" class="block text-sm font-bold text-gray-700 mb-2">Daftar Bahan (Pisahkan dengan koma atau baris baru)</label>
                <textarea name="bahan" id="bahan" rows="4" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm placeholder-gray-400" placeholder="Contoh:&#10;Tepung Terigu&#10;Mentega&#10;Gula Pasir">{{ old('bahan', $inputBahan) }}</textarea>
                
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg shadow-indigo-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Cari Rekomendasi
                    </button>
                </div>
            </form>
        </div>

        @if(isset($rekomendasiList) && count($rekomendasiList) > 0)
            <div class="space-y-8">
                @foreach($rekomendasiList as $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 capitalize"><span class="text-indigo-600">Pencarian:</span> "{{ $item['keyword'] }}"</h3>
                            @if(!$item['ditemukan'])
                                <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full">Tidak Ditemukan</span>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            @if($item['ditemukan'])
                                @if($item['is_single'])
                                    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 text-sm px-4 py-3 rounded-xl flex items-start">
                                        <svg class="w-5 h-5 mr-2 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p>Barang ini hanya memiliki 1 macam varian kualitas di katalog kami saat ini. Kedua pilihan di bawah mengarah pada barang yang sama.</p>
                                    </div>
                                @endif
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    {{-- Kualitas Rendah / Termurah --}}
                                    <div class="border-2 border-green-100 bg-green-50/30 rounded-xl p-5 hover:border-green-300 transition relative">
                                        <div class="absolute top-0 right-0 bg-green-500 text-white text-[10px] font-black uppercase px-3 py-1 rounded-bl-lg rounded-tr-lg">
                                            Kualitas Standar / Termurah
                                        </div>
                                        <div class="flex items-start mt-3">
                                            @if($item['rendah']->gambar)
                                                <img src="{{ asset('storage/' . $item['rendah']->gambar) }}" alt="{{ $item['rendah']->nama_barang }}" class="w-20 h-20 object-cover rounded-lg shadow-sm border border-gray-200">
                                            @else
                                                <div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg border border-gray-200">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                            <div class="ml-4 flex-1">
                                                <h4 class="font-bold text-gray-900 text-lg">{{ $item['rendah']->nama_barang }}</h4>
                                                <p class="text-gray-500 text-xs mt-1">{{ $item['rendah']->kategori->nama_kategori ?? 'Tanpa Kategori' }}</p>
                                                <p class="text-green-600 font-black text-xl mt-2">Rp {{ number_format($item['rendah']->harga, 0, ',', '.') }}</p>
                                                <p class="text-xs text-gray-500 mt-1">Stok: {{ $item['rendah']->stok }} {{ $item['rendah']->satuan }}</p>
                                                
                                                <form action="{{ route('pelanggan.keranjang.store') }}" method="POST" class="mt-4">
                                                    @csrf
                                                    <input type="hidden" name="barang_id" value="{{ $item['rendah']->id }}">
                                                    <input type="hidden" name="qty" value="1">
                                                    <button type="submit" class="w-full bg-white border-2 border-green-500 text-green-600 hover:bg-green-50 font-bold py-2 px-4 rounded-lg transition text-sm flex justify-center items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                        Masukkan Keranjang
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Kualitas Tinggi / Termahal --}}
                                    <div class="border-2 border-yellow-200 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-5 hover:border-yellow-400 transition relative shadow-[0_0_15px_rgba(251,191,36,0.1)]">
                                        <div class="absolute top-0 right-0 bg-gradient-to-r from-yellow-500 to-amber-500 text-white text-[10px] font-black uppercase px-3 py-1 rounded-bl-lg rounded-tr-lg shadow-sm">
                                            Kualitas Tinggi / Premium
                                        </div>
                                        <div class="flex items-start mt-3">
                                            @if($item['tinggi']->gambar)
                                                <img src="{{ asset('storage/' . $item['tinggi']->gambar) }}" alt="{{ $item['tinggi']->nama_barang }}" class="w-20 h-20 object-cover rounded-lg shadow-sm border border-gray-200">
                                            @else
                                                <div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg border border-gray-200">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                            <div class="ml-4 flex-1">
                                                <h4 class="font-bold text-gray-900 text-lg">{{ $item['tinggi']->nama_barang }}</h4>
                                                <p class="text-gray-500 text-xs mt-1">{{ $item['tinggi']->kategori->nama_kategori ?? 'Tanpa Kategori' }}</p>
                                                <p class="text-yellow-700 font-black text-xl mt-2">Rp {{ number_format($item['tinggi']->harga, 0, ',', '.') }}</p>
                                                <p class="text-xs text-gray-500 mt-1">Stok: {{ $item['tinggi']->stok }} {{ $item['tinggi']->satuan }}</p>
                                                
                                                <form action="{{ route('pelanggan.keranjang.store') }}" method="POST" class="mt-4">
                                                    @csrf
                                                    <input type="hidden" name="barang_id" value="{{ $item['tinggi']->id }}">
                                                    <input type="hidden" name="qty" value="1">
                                                    <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 text-white font-bold py-2 px-4 rounded-lg transition text-sm shadow-md flex justify-center items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                        Masukkan Keranjang
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-gray-500">Maaf, bahan "{{ $item['keyword'] }}" tidak ditemukan di katalog kami.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($inputBahan)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Tidak ada bahan yang ditemukan</h3>
                <p class="text-gray-500">Silakan periksa kembali ejaan bahan yang Anda cari.</p>
            </div>
        @endif
    </div>
</div>
</x-app-layout>
