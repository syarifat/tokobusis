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
        $budget = $request->input('budget');
        $porsi = $request->input('porsi');
        $kualitas = $request->input('kualitas');

        $rekomendasiList = [];
        $totalCost = 0;
        $statusRekomendasi = null; // 'sukses', 'kurang_budget'
        $opsi1 = null; // Budget menyesuaikan
        $opsi2 = null; // Porsi menyesuaikan
        $opsi3 = null; // Kualitas menyesuaikan

        if ($inputBahan && $budget && $porsi && $kualitas) {
            $barisBahan = preg_split('/[\n,]+/', $inputBahan);
            
            // Asumsi 1 unit barang (misal 1 kg/liter) cukup untuk 10 porsi
            $qtyPerBahan = max(1, ceil($porsi / 10));

            $itemsDipilih = [];
            $itemsSedang = []; // untuk opsi 3 jika kualitas tinggi
            $totalCostSedang = 0;

            foreach ($barisBahan as $bahan) {
                $bahan = trim($bahan);
                if (empty($bahan)) continue;

                $barangs = Barang::where('nama_barang', 'like', "%{$bahan}%")
                            ->where('stok', '>', 0)
                            ->get();

                if ($barangs->count() > 0) {
                    $termurah = $barangs->sortBy('harga')->first();
                    $termahal = $barangs->sortByDesc('harga')->first();

                    $itemPilihan = ($kualitas == 'tinggi') ? $termahal : $termurah;
                    $totalCost += ($itemPilihan->harga * $qtyPerBahan);
                    
                    $itemsDipilih[] = [
                        'keyword' => $bahan,
                        'barang' => $itemPilihan,
                        'qty' => $qtyPerBahan,
                        'subtotal' => $itemPilihan->harga * $qtyPerBahan
                    ];

                    $totalCostSedang += ($termurah->harga * $qtyPerBahan);
                    $itemsSedang[] = [
                        'keyword' => $bahan,
                        'barang' => $termurah,
                        'qty' => $qtyPerBahan,
                        'subtotal' => $termurah->harga * $qtyPerBahan
                    ];
                }
            }

            if (count($itemsDipilih) > 0) {
                if ($budget >= $totalCost) {
                    $statusRekomendasi = 'sukses';
                    $rekomendasiList = $itemsDipilih;
                } else {
                    $statusRekomendasi = 'kurang_budget';
                    
                    // Opsi 1: Budget menyesuaikan barang
                    $opsi1 = [
                        'judul' => 'Prioritas Kebutuhan (Ideal)',
                        'deskripsi' => 'Tetap menggunakan kualitas ' . ucfirst($kualitas) . ' untuk ' . $porsi . ' porsi. Anda perlu menambah budget.',
                        'budget_dibutuhkan' => $totalCost,
                        'items' => $itemsDipilih
                    ];

                    // Opsi 2: Porsi menyesuaikan budget
                    // Cari rasio budget terhadap total cost
                    $rasio = $budget / $totalCost;
                    $porsiMampu = floor($porsi * $rasio);
                    if ($porsiMampu < 1) $porsiMampu = 1;
                    $qtyMampu = max(1, ceil($porsiMampu / 10));
                    $costMampu = 0;
                    $itemsMampu = [];
                    foreach ($itemsDipilih as $item) {
                        $costMampu += ($item['barang']->harga * $qtyMampu);
                        $itemsMampu[] = [
                            'keyword' => $item['keyword'],
                            'barang' => $item['barang'],
                            'qty' => $qtyMampu,
                            'subtotal' => $item['barang']->harga * $qtyMampu
                        ];
                    }

                    $opsi2 = [
                        'judul' => 'Prioritas Budget (Porsi Dikurangi)',
                        'deskripsi' => 'Dengan budget Rp ' . number_format($budget, 0, ',', '.') . ', estimasi maksimal hanya cukup untuk sekitar ' . $porsiMampu . ' porsi (Kualitas ' . ucfirst($kualitas) . ').',
                        'budget_dibutuhkan' => $costMampu,
                        'items' => $itemsMampu
                    ];

                    // Opsi 3: Kualitas menyesuaikan budget (Hanya jika memilih tinggi)
                    if ($kualitas == 'tinggi' && $totalCostSedang <= $budget) {
                        $opsi3 = [
                            'judul' => 'Kompromi Kualitas (Standar)',
                            'deskripsi' => 'Menurunkan kualitas bahan ke tingkat Standar. Porsi tetap ' . $porsi . ' porsi dan masuk dalam budget.',
                            'budget_dibutuhkan' => $totalCostSedang,
                            'items' => $itemsSedang
                        ];
                    } elseif ($kualitas == 'tinggi') {
                        // Jika diturunkan pun tetap kurang
                        $opsi3 = [
                            'judul' => 'Kompromi Kualitas (Standar)',
                            'deskripsi' => 'Menurunkan kualitas bahan ke tingkat Standar, namun budget Anda tetap kurang. Butuh Rp ' . number_format($totalCostSedang, 0, ',', '.'),
                            'budget_dibutuhkan' => $totalCostSedang,
                            'items' => $itemsSedang
                        ];
                    }
                }
            }
        }

        return view('pelanggan.rekomendasi.index', compact(
            'inputBahan', 'budget', 'porsi', 'kualitas', 
            'rekomendasiList', 'totalCost', 'statusRekomendasi',
            'opsi1', 'opsi2', 'opsi3'
        ));
    }
}
