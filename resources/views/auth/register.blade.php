<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Buat Akun Baru ✨</h2>
        <p class="text-sm text-gray-500 mt-1">Lengkapi data di bawah ini untuk mulai berbelanja.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                   class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                   placeholder="Sesuai nama penerima">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div>
            <label for="no_hp" class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp</label>
            <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required autocomplete="tel" 
                   class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                   placeholder="Contoh: 081234567890">
            <x-input-error :messages="$errors->get('no_hp')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div>
            <label for="alamat" class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap Pengiriman</label>
            <textarea id="alamat" name="alamat" required rows="3"
                      class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                      placeholder="Nama Jalan, RT/RW, Dusun, Desa, Kecamatan">{{ old('alamat') }}</textarea>
            <x-input-error :messages="$errors->get('alamat')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                   class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                   placeholder="Untuk login dan notifikasi">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-xs font-bold" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" 
                       class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                       placeholder="Minimal 8 karakter">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-xs font-bold" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Ulangi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                       class="w-full border-gray-300 text-gray-900 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-3 text-sm placeholder-gray-400" 
                       placeholder="Ketik ulang password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-xs font-bold" />
            </div>
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