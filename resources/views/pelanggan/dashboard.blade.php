<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Produk Toko Bu Sis') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ 
        selectedItems: [], 
        toggleSelect(id, currentQty) {
            const index = this.selectedItems.findIndex(item => item.id === id);
            if (index > -1) {
                this.selectedItems.splice(index, 1);
            } else {
                this.selectedItems.push({ id: id, qty: currentQty });
            }
        },
        updateGlobalQty(id, newQty) {
            const index = this.selectedItems.findIndex(item => item.id === id);
            if (index > -1) {
                this.selectedItems[index].qty = newQty;
            }
        },
        isSelected(id) {
            return this.selectedItems.some(item => item.id === id);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="relative overflow-hidden rounded-2xl mb-8 bg-gradient-to-r from-blue-500 to-cyan-400 shadow-lg">
                <div class="px-8 py-12 text-center relative z-10">
                    <h1 class="text-4xl font-black text-white mb-2 tracking-tighter uppercase">TOKOBUSIS</h1>
                    <p class="text-white text-lg font-medium mb-6 opacity-90 italic">⭐ Belanja mudah, hemat dan terpercaya ⭐</p>
                </div>
            </div>

            <div x-show="selectedItems.length > 0" 
                 x-transition class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">
                <div class="bg-indigo-600 text-white p-4 rounded-2xl shadow-2xl flex justify-between items-center border border-white/20">
                    <div>
                        <span class="font-bold text-lg" x-text="selectedItems.length"></span>
                        <span class="text-sm ml-1">Barang terpilih</span>
                    </div>
                    <button @click="$refs.bulkForm.submit()" class="bg-white text-indigo-600 px-6 py-2 rounded-xl font-black text-sm hover:bg-gray-100 transition">
                        + MASUKKAN KERANJANG
                    </button>
                </div>
            </div>

            <form x-ref="bulkForm" action="{{ route('pelanggan.keranjang.bulkStore') }}" method="POST" style="display:none;">
                @csrf
                <template x-for="(item, index) in selectedItems" :key="item.id">
                    <div>
                        <input type="hidden" :name="'items['+index+'][id]'" :value="item.id">
                        <input type="hidden" :name="'items['+index+'][qty]'" :value="item.qty">
                    </div>
                </template>
            </form>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @forelse ($barangs as $barang)
                    <div class="bg-white rounded-3xl shadow-sm border-2 overflow-hidden flex flex-col group transition-all duration-300"
                         x-data="{ localQty: 1 }"
                         :class="isSelected({{ $barang->id }}) ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-100'">
                        
                        <div class="relative h-44 md:h-52 bg-gray-50 overflow-hidden cursor-pointer" @click="toggleSelect({{ $barang->id }}, localQty)">
                            @if($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @endif
                            <div class="absolute top-3 right-3">
                                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                     :class="isSelected({{ $barang->id }}) ? 'bg-indigo-500 border-indigo-500' : 'bg-white/50 border-gray-300'">
                                    <svg x-show="isSelected({{ $barang->id }})" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 flex-1 flex flex-col text-center">
                            <h3 class="text-base font-bold text-gray-800 mb-1">{{ $barang->nama_barang }}</h3>
                            <div class="text-xl font-black text-blue-600 mb-3">Rp {{ number_format($barang->harga, 0, ',', '.') }}</div>
                            
                            <div class="mt-auto">
                                @if($barang->stok > 0)
                                    <div class="flex items-center justify-center bg-gray-100 rounded-xl p-1 border border-gray-200 mb-3">
                                        <button type="button" @click="if(localQty > 1) { localQty--; updateGlobalQty({{ $barang->id }}, localQty); }" class="w-10 h-10 flex items-center justify-center text-blue-600 font-black text-xl hover:bg-white rounded-lg transition">-</button>
                                        <input type="number" readonly x-model="localQty" class="w-12 bg-transparent border-none text-center font-bold text-gray-800 focus:ring-0">
                                        <button type="button" @click="if(localQty < {{ $barang->stok }}) { localQty++; updateGlobalQty({{ $barang->id }}, localQty); }" class="w-10 h-10 flex items-center justify-center text-blue-600 font-black text-xl hover:bg-white rounded-lg transition">+</button>
                                    </div>

                                    <form action="{{ route('pelanggan.keranjang.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                                        <input type="hidden" name="qty" :value="localQty">
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 rounded-xl text-xs transition shadow-md uppercase">
                                            + Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3 rounded-xl text-xs cursor-not-allowed">STOK HABIS</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center text-gray-400">Produk tidak ditemukan.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>