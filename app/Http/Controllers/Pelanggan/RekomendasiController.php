<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $inputBahan = $request->input('bahan');
        $rekomendasiList = [];

        if ($inputBahan) {
            // Memisahkan input berdasarkan koma atau baris baru
            $barisBahan = preg_split('/[\n,]+/', $inputBahan);
            
            foreach ($barisBahan as $bahan) {
                $bahan = trim($bahan);
                if (empty($bahan)) continue;

                // Cari barang berdasarkan nama (like) yang stoknya masih ada
                $barangs = Barang::where('nama_barang', 'like', "%{$bahan}%")
                            ->where('stok', '>', 0)
                            ->get();

                if ($barangs->count() > 0) {
                    $termurah = $barangs->sortBy('harga')->first();
                    $termahal = $barangs->sortByDesc('harga')->first();

                    $rekomendasiList[] = [
                        'keyword' => $bahan,
                        'ditemukan' => true,
                        'rendah' => $termurah,
                        'tinggi' => $termahal,
                        'is_single' => ($termurah->id === $termahal->id)
                    ];
                } else {
                    $rekomendasiList[] = [
                        'keyword' => $bahan,
                        'ditemukan' => false,
                        'rendah' => null,
                        'tinggi' => null,
                    ];
                }
            }
        }

        return view('pelanggan.rekomendasi.index', compact('rekomendasiList', 'inputBahan'));
    }
}
