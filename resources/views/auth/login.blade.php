<x-guest-layout>
    <x-auth-session-status class="mb-4 bg-green-50 text-green-600 p-3 rounded-xl text-sm font-bold text-center" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Selamat Datang! 👋</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk mulai berbelanja.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                   class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                   placeholder="contoh@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                   class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium">Ingat saya</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center px-4 py-3.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-white hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                Masuk ke Akun
            </button>
        </div>

        <p class="text-center text-sm text-gray-600 mt-6">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">Daftar sekarang</a>
        </p>
    </form>
</x-guest-layout>