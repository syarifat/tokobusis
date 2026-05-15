@if(count($items) > 0)
    <div class="mb-4 flex justify-between items-end border-b pb-4">
        <div>
            <h4 class="font-bold text-gray-800 text-lg">{{ $title ?? 'Detail Barang' }}</h4>
            <p class="text-sm text-gray-500">Estimasi Kuantitas & Harga per Item</p>
        </div>
        <form action="{{ route('pelanggan.keranjang.bulkStore') }}" method="POST">
            @csrf
            @foreach($items as $index => $item)
                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item['barang']->id }}">
                <input type="hidden" name="items[{{ $index }}][qty]" value="{{ $item['qty'] }}">
            @endforeach
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Masukkan Semua ke Keranjang
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($items as $item)
            <div class="border border-gray-100 rounded-xl p-4 hover:shadow-md transition bg-gray-50/50 flex flex-col h-full">
                <div class="flex items-start mb-3">
                    @if($item['barang']->gambar)
                        <img src="{{ asset('storage/' . $item['barang']->gambar) }}" alt="{{ $item['barang']->nama_barang }}" class="w-16 h-16 object-cover rounded-lg shadow-sm border border-gray-200">
                    @else
                        <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded-lg border border-gray-200">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <div class="ml-3">
                        <h5 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $item['barang']->nama_barang }}</h5>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $item['barang']->kategori->nama_kategori ?? 'Umum' }}</p>
                        <p class="text-indigo-600 font-bold text-xs mt-1">Rp {{ number_format($item['barang']->harga, 0, ',', '.') }}<span class="text-gray-400 font-normal">/{{ $item['barang']->satuan }}</span></p>
                    </div>
                </div>
                
                <div class="mt-auto pt-3 border-t border-gray-200 flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-600 bg-white border border-gray-200 px-2 py-1 rounded">Qty: {{ $item['qty'] }}</span>
                    <span class="text-sm font-black text-gray-900">Sub: Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-sm text-gray-500">Tidak ada barang yang dapat direkomendasikan.</p>
@endif
