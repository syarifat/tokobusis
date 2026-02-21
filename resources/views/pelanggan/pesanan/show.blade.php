<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan: {{ $pesanan->kode_pesanan }}
            </h2>
            <a href="{{ route('pelanggan.pesanan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-start border-b pb-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Status Pesanan</p>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $pesanan->status_pesanan == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $pesanan->status_pesanan }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Jenis Pesanan</p>
                                <p class="font-bold text-indigo-600">{{ ucfirst($pesanan->jenis_pesanan) }} {{ $pesanan->nama_event ? '('.$pesanan->nama_event.')' : '' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Tanggal Pengantaran:</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($pesanan->tanggal_pengantaran)->format('d M Y') }}</p>
                            </div>
                            @if($pesanan->jenis_pesanan == 'event')
                            <div>
                                <p class="text-gray-500">Tenggat Pelunasan:</p>
                                <p class="font-semibold text-red-600">{{ \Carbon\Carbon::parse($pesanan->tenggat_pembayaran)->format('d M Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-bold text-lg mb-4">Rincian Barang</h3>
                        <div class="space-y-4">
                            @foreach($pesanan->items as $item)
                            <div class="flex items-center justify-between border-b pb-4">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded mr-4 overflow-hidden">
                                        @if($item->barang->gambar)
                                            <img src="{{ asset('storage/'.$item->barang->gambar) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $item->barang->nama_barang }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->qty }} {{ $item->barang->satuan }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <p class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between mt-6 pt-4 border-t-2">
                            <p class="text-lg font-bold">Total Harga</p>
                            <p class="text-lg font-black text-indigo-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-indigo-500">
                        <h3 class="font-bold text-lg mb-4">Informasi Pembayaran</h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total Tagihan:</span>
                                <span class="font-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Sudah Dibayar:</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($pesanan->total_dibayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm border-t pt-2">
                                <span class="font-bold">Sisa Tagihan:</span>
                                <span class="font-bold text-red-600" id="sisa-tagihan-val">Rp {{ number_format($pesanan->total_harga - $pesanan->total_dibayar, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mb-6 text-center">
                            <p class="text-xs text-gray-500 mb-1">Status Pembayaran</p>
                            <span class="px-4 py-1 rounded-full text-sm font-black uppercase {{ $pesanan->status_pembayaran == 'lunas' ? 'bg-green-500 text-white' : 'bg-orange-500 text-white' }}">
                                {{ $pesanan->status_pembayaran }}
                            </span>
                        </div>

                        @if($pesanan->status_pembayaran != 'lunas')
                            <div class="space-y-3">
                                <p class="text-xs text-gray-500 italic text-center">*Klik tombol di bawah untuk melakukan pembayaran via Midtrans</p>
                                
                                @if($pesanan->jenis_pesanan == 'event')
                                    <div class="mt-4">
                                        <label class="block text-xs font-bold mb-1">Nominal Cicilan (Rp)</label>
                                        <input type="number" id="nominal_cicilan" class="w-full border-gray-300 rounded text-sm mb-2" placeholder="Masukkan jumlah bayar..." value="{{ $pesanan->total_harga - $pesanan->total_dibayar }}">
                                    </div>
                                @endif

                                <button type="button" id="pay-button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded shadow-lg transition duration-200">
                                    Bayar Sekarang
                                </button>
                            </div>
                        @else
                            <div class="bg-green-50 p-4 rounded text-center border border-green-200 text-green-700 font-bold text-sm">
                                Pesanan ini sudah lunas. Terima kasih!
                            </div>
                        @endif
                    </div>

                    @if($pesanan->jenis_pesanan == 'event' && $pesanan->pembayarans->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-bold text-sm mb-3">Histori Cicilan</h3>
                        <div class="space-y-3">
                            @foreach($pesanan->pembayarans->where('status_transaksi', 'sukses') as $bayar)
                            <div class="text-xs border-l-2 border-green-500 pl-3 py-1">
                                <p class="font-bold">Rp {{ number_format($bayar->nominal_bayar, 0, ',', '.') }}</p>
                                <p class="text-gray-500">{{ $bayar->waktu_bayar ? $bayar->waktu_bayar->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            
            if (payButton) {
                payButton.addEventListener('click', function () {
                    // Ambil nominal dari input jika ada (untuk event), jika tidak ambil sisa tagihan
                    const inputNominal = document.getElementById('nominal_cicilan');
                    const sisaTagihan = {{ $pesanan->total_harga - $pesanan->total_dibayar }};
                    const nominal = inputNominal ? parseInt(inputNominal.value) : sisaTagihan;

                    if (!nominal || nominal < 1000) {
                        alert("Minimal pembayaran adalah Rp 1.000");
                        return;
                    }

                    if (nominal > sisaTagihan) {
                        alert("Nominal bayar tidak boleh melebihi sisa tagihan (Rp " + sisaTagihan.toLocaleString('id-ID') + ")");
                        return;
                    }

                    // Disable button saat proses
                    payButton.disabled = true;
                    payButton.innerText = "Memproses...";

                    fetch("{{ route('pelanggan.pembayaran.pay', $pesanan->id) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({ nominal: nominal })
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || "Terjadi kesalahan pada server");
                        }
                        return data;
                    })
                    .then(data => {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                alert("Pembayaran Berhasil!");
                                location.reload();
                            },
                            onPending: function(result) {
                                alert("Menunggu Pembayaran...");
                                location.reload();
                            },
                            onError: function(result) {
                                alert("Pembayaran Gagal!");
                                payButton.disabled = false;
                                payButton.innerText = "Bayar Sekarang";
                            },
                            onClose: function() {
                                payButton.disabled = false;
                                payButton.innerText = "Bayar Sekarang";
                            }
                        });
                    })
                    .catch(error => {
                        alert(error.message);
                        payButton.disabled = false;
                        payButton.innerText = "Bayar Sekarang";
                    });
                });
            }
        });
    </script>
</x-app-layout>