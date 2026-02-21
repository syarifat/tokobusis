<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name' => 'Admin Toko Bu Sis',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_hp' => '081234567890',
            'alamat' => 'Toko Sembako Bu Sis, Kota Kediri', 
        ]);

        // Akun Pelanggan
        User::create([
            'name' => 'Pelanggan Testing',
            'email' => 'pelanggan@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'no_hp' => '089876543210',
            'alamat' => 'Jl. Contoh Pelanggan No. 123',
        ]);
    }
}