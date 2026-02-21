<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Barang;

class KategoriBarangSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Kategori Sesuai Migration (Hanya nama_kategori)
        $categories = [
            ['nama_kategori' => 'Sembako'],
            ['nama_kategori' => 'Minuman'],
            ['nama_kategori' => 'Camilan'],
            ['nama_kategori' => 'Bumbu Dapur'],
            ['nama_kategori' => 'Sabun & Deterjen'],
        ];

        foreach ($categories as $cat) {
            $kategori = Kategori::create($cat);

            // 2. Buat 10 Barang per Kategori
            $faker = \Faker\Factory::create('id_ID');

            for ($i = 1; $i <= 10; $i++) {
                $namaBarang = $this->getNamaBarangContoh($kategori->nama_kategori, $i);
                
                Barang::create([
                    'kategori_id' => $kategori->id,
                    'nama_barang' => $namaBarang,
                    'deskripsi'   => $faker->sentence(10),
                    'harga'       => $faker->numberBetween(20, 500) * 100,
                    'stok'        => $faker->numberBetween(10, 100),
                    'satuan'      => $this->getSatuanContoh($kategori->nama_kategori),
                    'gambar'      => null,
                ]);
            }
        }
    }

    private function getNamaBarangContoh($kategori, $index)
    {
        $data = [
            'Sembako' => ['Beras Premium', 'Minyak Goreng 2L', 'Gula Pasir', 'Telur Ayam', 'Tepung Terigu'],
            'Minuman' => ['Teh Kotak', 'Kopi Bubuk', 'Susu Kaleng', 'Air Mineral', 'Sirup'],
            'Camilan' => ['Wafer', 'Keripik', 'Kacang', 'Biskuit', 'Cokelat'],
            'Bumbu Dapur' => ['Garam', 'Penyedap Rasa', 'Kecap Manis', 'Saus Sambal', 'Merica'],
            'Sabun & Deterjen' => ['Sabun Mandi', 'Deterjen Bubuk', 'Shampoo', 'Cuci Piring', 'Pewangi'],
        ];

        $list = $data[$kategori] ?? ['Barang Umum'];
        return $list[($index - 1) % count($list)] . ' ' . $index;
    }

    private function getSatuanContoh($kategori)
    {
        if ($kategori == 'Minuman') return 'pcs';
        if ($kategori == 'Sembako') return 'kg';
        return 'pack';
    }
}