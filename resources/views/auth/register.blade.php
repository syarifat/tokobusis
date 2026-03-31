<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Buat Akun Baru ✨</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar sekarang dan nikmati kemudahannya.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                   class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                   placeholder="Nama sesuai KTP">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                   class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                   placeholder="contoh@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div>
            <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                   class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                   placeholder="Minimal 8 karakter">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                   class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                   placeholder="Ulangi password di atas">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center items-center px-4 py-3.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-white hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                Daftar Akun
            </button>
        </div>

        <p class="text-center text-sm text-gray-600 mt-6 pt-2 border-t border-gray-100">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>