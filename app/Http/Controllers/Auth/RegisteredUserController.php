<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // 1. Tambahkan validasi untuk no_hp dan alamat
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // 2. Simpan semua data ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'pelanggan',
        ]);

        event(new \Illuminate\Auth\Events\Registered($user));

        // HAPUS ATAU KOMENTARI BARIS INI AGAR TIDAK LANGSUNG LOGIN:
        // \Illuminate\Support\Facades\Auth::login($user);

        // 3. Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Silakan masuk menggunakan Email dan Password Anda.');
    }
}
