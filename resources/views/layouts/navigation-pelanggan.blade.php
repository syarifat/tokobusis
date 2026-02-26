@php
    // Menghitung jumlah barang di keranjang user yang sedang login
    $cartCount = \App\Models\Keranjang::where('user_id', Auth::id())->sum('qty');
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 gap-4">
            
            <div class="flex items-center shrink-0">
                <a href="{{ route('pelanggan.dashboard') }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 transition">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm2 4h12v10H6zm3 2v6h2v-6zm4 0v6h2v-6z"></path><path d="M3 4h18v2H3zm1 18h16a1 1 0 001-1V8H3v13a1 1 0 001 1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    <span class="font-black text-2xl tracking-tighter uppercase hidden sm:block">Tokobusis</span>
                </a>
            </div>

            <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                <form action="{{ route('pelanggan.dashboard') }}" method="GET" class="w-full relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk favorit kamu..." class="w-full pl-5 pr-12 py-2.5 bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-full text-sm shadow-sm transition-all duration-300">
                    <button type="submit" class="absolute right-0 top-0 mt-2.5 mr-4 text-gray-400 hover:text-blue-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>

            <div class="hidden sm:flex items-center gap-6">
                
                <a href="{{ route('pelanggan.event.index') }}" class="text-gray-500 hover:text-blue-600 transition relative" title="Pengajuan Event (Bon)">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </a>

                <a href="{{ route('pelanggan.pesanan.index') }}" class="text-gray-500 hover:text-blue-600 transition relative" title="Riwayat Pesanan">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </a>

                <a href="{{ route('pelanggan.keranjang.index') }}" class="text-gray-500 hover:text-blue-600 transition relative" title="Keranjang Belanja">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-full transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            <div class="bg-blue-100 text-blue-600 p-0.5 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-700 hidden lg:block">{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-sm py-1.5">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm py-1.5">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center sm:hidden gap-4">
                <a href="{{ route('pelanggan.keranjang.index') }}" class="text-gray-500 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">{{ $cartCount }}</span>
                    @endif
                </a>
                <button @click="open = ! open" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:bg-gray-100 transition rounded-md p-1">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="md:hidden pb-3">
             <form action="{{ route('pelanggan.dashboard') }}" method="GET" class="w-full relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-full pl-4 pr-10 py-2 bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-full text-sm">
                <button type="submit" class="absolute right-0 top-0 mt-2 mr-3 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></button>
            </form>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden absolute w-full bg-white border-b border-gray-200 shadow-lg z-50">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('pelanggan.pesanan.index')" :active="request()->routeIs('pelanggan.pesanan.*')">Riwayat Pesanan</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pelanggan.event.index')" :active="request()->routeIs('pelanggan.event.*')">Pengajuan Event</x-responsive-nav-link>
        </div>
        
        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
            <div class="px-4 flex items-center gap-3">
                <div class="bg-blue-100 text-blue-600 p-2 rounded-full">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            
            <div class="mt-3 space-y-1 bg-white">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>