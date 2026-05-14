<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Toko Bu Sis - Pusat Sembako & Kebutuhan Hajatan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans selection:bg-indigo-500 selection:text-white">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                        S
                    </div>
                    <span class="font-black text-xl tracking-tight text-gray-900">Toko Bu Sis</span>
                </div>
                
                <div>
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4">
                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">Dashboard Admin</a>
                                @else
                                    <a href="{{ route('pelanggan.dashboard') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">Mulai Belanja &rarr;</a>
                                @endif
                            @else
                                <a href="{{ route('pelanggan.dashboard') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">Lihat Katalog</a>
                                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 transition">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="font-bold bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-md">Daftar Sekarang</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-10 sm:pt-16 lg:pt-20 px-4 sm:px-6 lg:px-8">
                <main class="mx-auto max-w-7xl">
                    <div class="sm:text-center lg:text-left">
                        <span class="inline-block py-1 px-3 rounded-full bg-indigo-50 text-indigo-600 text-sm font-bold mb-4 border border-indigo-100">
                            🚀 Melayani Area Tulungagung & Sekitarnya
                        </span>
                        <h1 class="text-4xl tracking-tight font-black text-gray-900 sm:text-5xl md:text-6xl leading-tight">
                            <span class="block xl:inline">Belanja Sembako &</span>
                            <span class="block text-indigo-600 xl:inline">Kebutuhan Hajatan</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Solusi lengkap untuk kebutuhan dapur harian hingga acara besar (Pitonan, Nikahan, dll). Nikmati kemudahan pesan antar, bayar di tempat, hingga sistem Bon/Cicilan khusus event.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start gap-3">
                            <div class="rounded-md shadow">
                                @auth
                                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('pelanggan.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg transition">
                                        Masuk ke Toko
                                    </a>
                                @else
                                    <a href="{{ route('pelanggan.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg transition">
                                        Lihat Katalog
                                    </a>
                                @endauth
                            </div>
                            <div class="mt-3 sm:mt-0">
                                @guest
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-gray-300 text-base font-bold rounded-lg text-gray-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg transition">
                                    Daftar Sekarang
                                </a>
                                @else
                                <a href="#fitur" class="w-full flex items-center justify-center px-8 py-3 border border-gray-300 text-base font-bold rounded-lg text-gray-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg transition">
                                    Pelajari Fitur
                                </a>
                                @endguest
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-indigo-50 flex items-center justify-center p-12 hidden lg:flex">
            <div class="grid grid-cols-2 gap-4 w-full max-w-lg transform rotate-3">
                <div class="bg-white p-4 rounded-2xl shadow-xl">
                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-3">🚚</div>
                    <p class="font-bold text-gray-800">Kurir Cepat</p>
                    <p class="text-xs text-gray-500 mt-1">Lacak via Maps langsung ke depan pintu Anda.</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-xl transform translate-y-8">
                    <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-3">📝</div>
                    <p class="font-bold text-gray-800">Sistem Bon Event</p>
                    <p class="text-xs text-gray-500 mt-1">Hajatan lancar, bayar bisa dicicil belakangan.</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-xl">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-3">💳</div>
                    <p class="font-bold text-gray-800">Banyak Pilihan Bayar</p>
                    <p class="text-xs text-gray-500 mt-1">Terima Tunai (COD), Transfer, dan QRIS.</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-xl transform translate-y-8">
                    <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mb-3">📦</div>
                    <p class="font-bold text-gray-800">Grosir & Eceran</p>
                    <p class="text-xs text-gray-500 mt-1">Harga bersaing untuk pembelian partai besar.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="fitur" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-indigo-600 font-bold tracking-wide uppercase">Mengapa Toko Bu Sis?</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Lebih dari Sekadar Toko Kelontong
                </p>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Belanja Harian Praktis</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Dari beras, minyak, hingga bumbu dapur. Tinggal klik dari HP, pesanan langsung kami siapkan. Bisa diambil sendiri atau diantar kurir.</p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-purple-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">Unggulan</div>
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Sistem Bon Hajatan</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Punya acara pitonan atau nikahan? Ajukan Event di aplikasi, belanja sepuasnya tanpa bayar di awal. Pelunasan fleksibel hingga H+7 setelah acara.</p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Lokasi GPS Akurat</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Tidak perlu repot *shareloc* via WA. Sistem kami mendeteksi titik GPS rumah Anda secara otomatis agar kurir tidak tersesat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top 3 Barang Terlaris --}}
    @if(isset($topItems) && $topItems->count() > 0)
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                    <h2 class="text-base text-indigo-600 font-bold tracking-wide uppercase">Paling Dicari</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900">
                        Barang Terlaris Bulan Ini
                    </p>
                </div>
                <a href="{{ route('pelanggan.dashboard') }}" class="mt-4 md:mt-0 text-indigo-600 font-bold hover:text-indigo-800 transition flex items-center">
                    Lihat Semua <span class="ml-2">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($topItems as $index => $item)
                <div class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative">
                    <div class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-black px-3 py-1.5 rounded-full z-10 shadow-md">
                        #{{ $index + 1 }} Terlaris
                    </div>
                    <div class="aspect-w-4 aspect-h-3 bg-gray-200 relative overflow-hidden group-hover:opacity-90 transition h-64">
                        @if($item->gambar)
                            <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->nama_barang }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-gray-900 line-clamp-1 group-hover:text-indigo-600 transition">{{ $item->nama_barang }}</h3>
                        </div>
                        <p class="text-gray-500 text-sm line-clamp-2 mb-4">{{ $item->deskripsi ?? 'Kualitas terbaik untuk kebutuhan sehari-hari.' }}</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-black text-indigo-600">Rp {{ number_format($item->harga, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/{{ $item->satuan }}</span></p>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">Terjual: {{ $item->pesanan_items_sum_qty ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Galeri Toko --}}
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-base text-indigo-600 font-bold tracking-wide uppercase">Galeri Kami</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Potret Toko Bu Sis
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">Melayani dengan sepenuh hati, menyediakan produk segar dan berkualitas setiap hari.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Foto 1 (Besar Kiri) --}}
                <div class="col-span-2 md:row-span-2 rounded-2xl overflow-hidden shadow-md relative group min-h-[250px] md:min-h-[400px]">
                    <img src="{{ asset('img_landing/1.jpg') }}" alt="Galeri 1" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                </div>
                {{-- Foto 2 & 3 (Kanan Atas) --}}
                <div class="rounded-2xl overflow-hidden shadow-md relative group h-48 md:h-auto">
                    <img src="{{ asset('img_landing/2.jpg') }}" alt="Galeri 2" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-md relative group h-48 md:h-auto">
                    <img src="{{ asset('img_landing/3.jpg') }}" alt="Galeri 3" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                </div>
                {{-- Foto 4 (Kanan Bawah Lebar) --}}
                <div class="col-span-2 rounded-2xl overflow-hidden shadow-md relative group h-48 md:h-auto">
                    <img src="{{ asset('img_landing/4.jpg') }}" alt="Galeri 4" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                </div>
            </div>
        </div>
    </div>

    {{-- Lokasi Toko --}}
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-indigo-50 rounded-3xl overflow-hidden shadow-lg border border-indigo-100 flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-10 lg:p-16 flex flex-col justify-center">
                    <h2 class="text-base text-indigo-600 font-bold tracking-wide uppercase mb-2">Kunjungi Kami</h2>
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-4">Lokasi Toko Bu Sis</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Kami buka setiap hari untuk memenuhi kebutuhan harian Anda. Jangan ragu untuk mampir langsung ke toko kami atau klik tombol di bawah untuk melihat rute via Google Maps.
                    </p>
                    
                    <div class="flex items-center text-gray-700 mb-8 bg-white p-4 rounded-xl shadow-sm inline-block">
                        <svg class="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-medium">Tulungagung, Jawa Timur</span>
                    </div>

                    <div>
                        <a href="https://maps.app.goo.gl/8ki6XN8zUaKKWfS69?g_st=aw" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            Buka di Google Maps
                        </a>
                    </div>
                </div>
                <div class="w-full md:w-1/2 h-64 md:h-auto min-h-[400px] relative">
                    {{-- Iframe Google Maps (Menggunakan koordinat default/umum untuk preview, link tombol menuju URL spesifik) --}}
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126438.2854809817!2d111.82424074213198!3d-8.064507026721021!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78e2e2a33dbd4b%3A0xc62b460c50005d5f!2sTulungagung%2C%20Tulungagung%20Regency%2C%20East%20Java!5e0!3m2!1sen!2sid!4v1715690835474!5m2!1sen!2sid" 
                        class="absolute inset-0 w-full h-full border-0" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-indigo-700">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Siap untuk mulai belanja?</span>
                <span class="block text-indigo-200">Daftar sekarang dan nikmati kemudahannya.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0 gap-3">
                <div class="inline-flex rounded-md shadow gap-3">
                    @auth
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('pelanggan.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-bold rounded-md text-indigo-600 bg-white hover:bg-indigo-50 transition">
                            Masuk Dashboard
                        </a>
                    @else
                        <a href="{{ route('pelanggan.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-bold rounded-md text-indigo-600 bg-white hover:bg-indigo-50 transition">
                            Lihat Katalog
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-bold rounded-md text-white bg-indigo-500 hover:bg-indigo-400 transition">
                            Buat Akun Gratis
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-indigo-600 rounded flex items-center justify-center text-white font-bold text-xs">S</div>
                <span class="font-bold text-gray-900">Toko Bu Sis</span>
            </div>
            <p class="text-sm text-gray-500 text-center md:text-left">
                &copy; {{ date('Y') }} Toko Bu Sis Tulungagung. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>