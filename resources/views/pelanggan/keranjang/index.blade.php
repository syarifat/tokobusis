<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja Saya') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        // Ambil data keranjang dari backend dan ubah jadi array Javascript
        items: {{ json_encode($keranjangs->map(function($k) { 
            return ['id' => $k->id, 'subtotal' => $k->barang->harga * $k->qty, 'selected' => false]; 
        })) }},
        
        // Hitung total harga otomatis
        get total() {
            return this.items.filter(i => i.selected).reduce((sum, i) => sum + i.subtotal, 0);
        },
        
        // Hitung jumlah barang yang dicentang
        get selectedCount() {
            return this.items.filter(i => i.selected).length;
        },
        
        // Cek apakah semua tercentang
        get allSelected() {
            return this.items.length > 0 && this.selectedCount === this.items.length;
        },
        
        // Fungsi centang semua
        toggleAll() {
            let state = !this.allSelected;
            this.items.forEach(i => i.selected = state);
        },
        
        // Format rupiah
        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(count($keranjangs) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal mb-6">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center w-12">
                                            <input type="checkbox" :checked="allSelected" @change="toggleAll()" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Satuan</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($keranjangs as $index => $item)
                                        @php $subtotal = $item->barang->harga * $item->qty; @endphp
                                        <tr :class="items[{{ $index }}].selected ? 'bg-indigo-50/30' : ''" class="transition-colors duration-150">
                                            
                                            <td class="px-5 py-5 border-b border-gray-200 text-center">
                                                <input type="checkbox" x-model="items[{{ $index }}].selected" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                            </td>

                                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-12 h-12 bg-gray-200 rounded">
                                                        @if($item->barang->gambar)
                                                            <img class="w-full h-full rounded object-cover" src="{{ asset('storage/' . $item->barang->gambar) }}" alt="" />
                                                        @endif
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-gray-900 whitespace-no-wrap font-semibold">
                                                            {{ $item->barang->nama_barang }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                                Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                                            </td>
                                            
                                            <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                                <form action="{{ route('pelanggan.keranjang.update', $item->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="qty" value="{{ $item->qty }}" min="1" max="{{ $item->barang->stok }}" class="w-16 border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-sm p-1">
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1 rounded" title="Update Jumlah">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                            
                                            <td class="px-5 py-5 border-b border-gray-200 text-sm text-right font-semibold text-gray-700">
                                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            </td>
                                            
                                            <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                                <form action="{{ route('pelanggan.keranjang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini dari keranjang?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-center bg-gray-50 p-6 rounded-lg border border-gray-200 mt-4 sticky bottom-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                            <div class="mb-4 md:mb-0">
                                <a href="{{ route('pelanggan.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Lanjut Belanja
                                </a>
                            </div>
                            <div class="text-right flex flex-col md:flex-row items-center gap-6">
                                <div>
                                    <span class="text-gray-600">Total (<span x-text="selectedCount"></span> produk):</span>
                                    <span class="text-2xl font-bold text-indigo-600 block md:inline md:ml-2">Rp <span x-text="formatRupiah(total)"></span></span>
                                </div>
                                
                                <form action="{{ route('pelanggan.checkout.index') }}" method="GET">
                                    <template x-for="item in items.filter(i => i.selected)" :key="item.id">
                                        <input type="hidden" name="selected_items[]" :value="item.id">
                                    </template>
                                    
                                    <button type="submit" 
                                            :disabled="selectedCount === 0" 
                                            :class="selectedCount === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'" 
                                            class="text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200">
                                        Checkout
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">Keranjang Belanja Masih Kosong</h3>
                            <p class="text-gray-500 mb-6">Yuk, mulai pilih produk sembako kebutuhanmu sekarang!</p>
                            <a href="{{ route('pelanggan.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">
                                Mulai Belanja
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>