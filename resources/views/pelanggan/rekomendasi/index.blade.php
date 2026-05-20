<x-app-layout>
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900">Sistem Rekomendasi Pintar</h2>
            <p class="text-gray-500 mt-2">Kalkulasi otomatis kebutuhan bahan masakan sesuai porsi, budget, dan selera Anda.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-10 opacity-5 pointer-events-none">
                <svg class="w-32 h-32 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
            </div>
            
            <form action="{{ route('pelanggan.rekomendasi.index') }}" method="GET" class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="bahan" class="block text-sm font-bold text-gray-700 mb-2">Daftar Bahan Pokok (Pisahkan dengan baris baru)</label>
                    <textarea name="bahan" id="bahan" rows="4" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm placeholder-gray-400" placeholder="Contoh:&#10;Beras&#10;Minyak Goreng&#10;Gula Pasir" required>{{ old('bahan', $inputBahan ?? '') }}</textarea>
                </div>

                <div x-data="{
                    rawBudget: '{{ old('budget', $budget ?? '') }}',
                    get formattedBudget() {
                        if (!this.rawBudget) return '';
                        return parseInt(this.rawBudget.toString().replace(/\D/g, '') || 0).toLocaleString('id-ID');
                    },
                    set formattedBudget(value) {
                        this.rawBudget = value.replace(/\D/g, '');
                    }
                }">
                    <label for="budget" class="block text-sm font-bold text-gray-700 mb-2">Budget Maksimal (Rp)</label>
                    <input type="text" x-model="formattedBudget" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm" placeholder="Contoh: 500.000" required>
                    <input type="hidden" name="budget" id="budget" x-model="rawBudget">
                </div>

                <div>
                    <label for="porsi" class="block text-sm font-bold text-gray-700 mb-2">Estimasi Untuk Berapa Porsi/Orang?</label>
                    <input type="number" name="porsi" id="porsi" min="1" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm" placeholder="Contoh: 50" value="{{ old('porsi', $porsi ?? '') }}" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kualitas Bahan</label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="kualitas" value="sedang" class="peer sr-only" {{ (old('kualitas', $kualitas ?? 'sedang') == 'sedang') ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition text-center">
                                <span class="block font-bold text-gray-900 peer-checked:text-green-700">Kualitas Standar (Sedang)</span>
                                <span class="text-xs text-gray-500">Ekonomis & ramah di kantong</span>
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="kualitas" value="tinggi" class="peer sr-only" {{ (old('kualitas', $kualitas ?? 'sedang') == 'tinggi') ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:bg-gray-50 transition text-center">
                                <span class="block font-bold text-gray-900 peer-checked:text-amber-700">Kualitas Premium (Tinggi)</span>
                                <span class="text-xs text-gray-500">Kualitas terbaik untuk acara spesial</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="md:col-span-2 mt-2 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg shadow-indigo-200 flex items-center w-full md:w-auto justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Hitung & Rekomendasikan
                    </button>
                </div>
            </form>
        </div>

        @if(isset($statusRekomendasi))
            @if($statusRekomendasi == 'sukses')
                <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-r-2xl shadow-sm mb-6 flex items-start">
                    <div class="bg-green-100 text-green-600 rounded-full p-2 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-green-800">Rekomendasi Ditemukan! Budget Anda Sangat Mencukupi.</h3>
                        <p class="text-green-700 mt-1">Kami telah memilihkan barang dengan kualitas <b>{{ ucfirst($kualitas) }}</b> untuk <b>{{ $porsi }}</b> porsi. Total estimasi biaya adalah <b>Rp {{ number_format($totalCost, 0, ',', '.') }}</b> (Sisa budget: Rp {{ number_format($budget - $totalCost, 0, ',', '.') }}).</p>
                    </div>
                </div>

                {{-- Menampilkan daftar item --}}
                @include('pelanggan.rekomendasi.partials.item-list', ['items' => $rekomendasiList, 'title' => 'Daftar Belanja Anda'])

            @elseif($statusRekomendasi == 'kurang_budget')
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-2xl shadow-sm mb-8 flex items-start">
                    <div class="bg-red-100 text-red-600 rounded-full p-2 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-red-800">Budget Tidak Memenuhi Kebutuhan</h3>
                        <p class="text-red-700 mt-1">Budget Anda (Rp {{ number_format($budget, 0, ',', '.') }}) tidak mencukupi untuk membeli seluruh bahan kualitas {{ ucfirst($kualitas) }} sebanyak {{ $porsi }} porsi. Jangan khawatir, sistem kami menyiapkan opsi cerdas berikut:</p>
                    </div>
                </div>

                <div class="space-y-10">
                    {{-- Opsi 1 --}}
                    @if(isset($opsi1))
                        <div class="border-2 border-indigo-100 rounded-2xl overflow-hidden shadow-sm relative">
                            <div class="absolute top-0 right-0 bg-indigo-100 text-indigo-700 text-xs font-black px-3 py-1 rounded-bl-lg">OPSI 1</div>
                            <div class="bg-indigo-50/50 p-6 border-b border-indigo-100">
                                <h3 class="text-xl font-bold text-indigo-900">{{ $opsi1['judul'] }}</h3>
                                <p class="text-indigo-700 mt-1">{{ $opsi1['deskripsi'] }} <span class="font-bold text-red-600">Total Butuh: Rp {{ number_format($opsi1['budget_dibutuhkan'], 0, ',', '.') }}</span></p>
                            </div>
                            <div class="p-6 bg-white">
                                @include('pelanggan.rekomendasi.partials.item-list', ['items' => $opsi1['items']])
                            </div>
                        </div>
                    @endif

                    {{-- Opsi 2 --}}
                    @if(isset($opsi2))
                        <div class="border-2 border-amber-100 rounded-2xl overflow-hidden shadow-sm relative">
                            <div class="absolute top-0 right-0 bg-amber-100 text-amber-700 text-xs font-black px-3 py-1 rounded-bl-lg">OPSI 2</div>
                            <div class="bg-amber-50/50 p-6 border-b border-amber-100">
                                <h3 class="text-xl font-bold text-amber-900">{{ $opsi2['judul'] }}</h3>
                                <p class="text-amber-700 mt-1">{!! str_replace('Rp '.number_format($budget, 0, ',', '.'), '<b>Rp '.number_format($budget, 0, ',', '.').'</b>', $opsi2['deskripsi']) !!} <span class="font-bold text-green-600">Total Biaya: Rp {{ number_format($opsi2['budget_dibutuhkan'], 0, ',', '.') }}</span></p>
                            </div>
                            <div class="p-6 bg-white">
                                @include('pelanggan.rekomendasi.partials.item-list', ['items' => $opsi2['items']])
                            </div>
                        </div>
                    @endif

                    {{-- Opsi 3 --}}
                    @if(isset($opsi3))
                        <div class="border-2 border-green-100 rounded-2xl overflow-hidden shadow-sm relative">
                            <div class="absolute top-0 right-0 bg-green-100 text-green-700 text-xs font-black px-3 py-1 rounded-bl-lg">OPSI 3</div>
                            <div class="bg-green-50/50 p-6 border-b border-green-100">
                                <h3 class="text-xl font-bold text-green-900">{{ $opsi3['judul'] }}</h3>
                                <p class="text-green-700 mt-1">{{ $opsi3['deskripsi'] }} <span class="font-bold text-green-700">Total Biaya: Rp {{ number_format($opsi3['budget_dibutuhkan'], 0, ',', '.') }}</span></p>
                            </div>
                            <div class="p-6 bg-white">
                                @include('pelanggan.rekomendasi.partials.item-list', ['items' => $opsi3['items']])
                            </div>
                        </div>
                    @endif
                </div>

            @endif
        @elseif($inputBahan && !isset($statusRekomendasi))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Tidak ada bahan yang ditemukan</h3>
                <p class="text-gray-500">Sistem tidak menemukan barang yang sesuai dengan kata kunci di katalog kami.</p>
            </div>
        @endif
    </div>
</div>
</x-app-layout>
