<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    // Dataset Internal Laravel (Menggantikan Python ML Dataset)
    // Berisi rasio konversi: Berapa porsi yang bisa dihasilkan dari 1 Kg, 1 Liter, atau 1 Pcs barang.
    private $mlDataset = [
        // Sembako Pokok
        'beras' => ['base' => 'kg', 'porsi' => 10], // 1 Kg Beras = 10 Porsi
        'minyak' => ['base' => 'l', 'porsi' => 20], // 1 Liter Minyak = 20 Porsi
        'gula' => ['base' => 'kg', 'porsi' => 30],
        'telur' => ['base' => 'kg', 'porsi' => 15],
        'tepung' => ['base' => 'kg', 'porsi' => 25],
        'mentega' => ['base' => 'kg', 'porsi' => 40],
        'margarin' => ['base' => 'kg', 'porsi' => 40],
        
        // Protein & Lauk
        'ayam' => ['base' => 'kg', 'porsi' => 8],
        'daging' => ['base' => 'kg', 'porsi' => 8],
        'ikan' => ['base' => 'kg', 'porsi' => 6],
        'tahu' => ['base' => 'pcs', 'porsi' => 2],
        'tempe' => ['base' => 'pcs', 'porsi' => 3],
        'sosis' => ['base' => 'kg', 'porsi' => 15],
        'bakso' => ['base' => 'kg', 'porsi' => 15],

        // Bumbu Dapur Dasar
        'garam' => ['base' => 'kg', 'porsi' => 200], // 1 kg garam bisa untuk 200 porsi
        'merica' => ['base' => 'kg', 'porsi' => 300],
        'lada' => ['base' => 'kg', 'porsi' => 300],
        'ketumbar' => ['base' => 'kg', 'porsi' => 250],
        'kemiri' => ['base' => 'kg', 'porsi' => 100],
        'kunyit' => ['base' => 'kg', 'porsi' => 150],
        'jahe' => ['base' => 'kg', 'porsi' => 150],
        'lengkuas' => ['base' => 'kg', 'porsi' => 150],
        'bawang merah' => ['base' => 'kg', 'porsi' => 30],
        'bawang putih' => ['base' => 'kg', 'porsi' => 40],
        'cabai' => ['base' => 'kg', 'porsi' => 20],
        'cabe' => ['base' => 'kg', 'porsi' => 20],
        'asam' => ['base' => 'kg', 'porsi' => 100],
        
        // Penyedap & Saus
        'penyedap' => ['base' => 'kg', 'porsi' => 250],
        'kaldu' => ['base' => 'kg', 'porsi' => 200],
        'royco' => ['base' => 'pcs', 'porsi' => 5], // Asumsi 1 sachet = 5 porsi
        'masako' => ['base' => 'pcs', 'porsi' => 5],
        'kecap' => ['base' => 'l', 'porsi' => 40],
        'saus' => ['base' => 'l', 'porsi' => 30],
        'saos' => ['base' => 'l', 'porsi' => 30],
        'santan' => ['base' => 'l', 'porsi' => 15],
        'kara' => ['base' => 'pcs', 'porsi' => 5],
        
        // Lain-lain
        'kopi' => ['base' => 'kg', 'porsi' => 50],
        'teh' => ['base' => 'pcs', 'porsi' => 20], // 1 box teh
        'mie' => ['base' => 'kg', 'porsi' => 12],
        'bihun' => ['base' => 'kg', 'porsi' => 15],
        'kerupuk' => ['base' => 'kg', 'porsi' => 30],
        'susu' => ['base' => 'l', 'porsi' => 5],
    ];

    /**
     * AI/NLP Extraction Engine
     * Ekstrak ukuran asli dari nama barang, misal: "Minyak Sunco 2L", "Beras 5kg", "Garam 250gr"
     */
    private function extractSizeMultiplier($namaBarang, $satuanDatabase, $datasetRule)
    {
        $namaBarang = strtolower($namaBarang);
        
        // Regex untuk menangkap angka dan satuan: e.g., 5kg, 5 kg, 500gr, 500 gram, 2L, 2 Liter, 250ml
        if (preg_match('/([0-9.,]+)\s*(kg|kilogram|g|gr|gram|l|liter|ml|mili)/i', $namaBarang, $matches)) {
            $value = (float) str_replace(',', '.', $matches[1]);
            $unit = strtolower($matches[2]);

            // Standarisasi ke 'kg' atau 'l'
            if (in_array($unit, ['g', 'gr', 'gram'])) {
                $value = $value / 1000; // convert to kg
                $unit = 'kg';
            } elseif (in_array($unit, ['ml', 'mili'])) {
                $value = $value / 1000; // convert to l
                $unit = 'l';
            } elseif (in_array($unit, ['kilogram'])) {
                $unit = 'kg';
            } elseif (in_array($unit, ['liter'])) {
                $unit = 'l';
            }

            // Jika base di dataset cocok dengan ekstraksi
            if ($datasetRule['base'] == $unit || ($datasetRule['base'] == 'kg' && $unit == 'l')) {
                return $value; 
            }
        }

        // Jika tidak ada di nama, cek satuan di database
        $satuanDatabase = strtolower($satuanDatabase);
        if ($satuanDatabase == 'kg' || $satuanDatabase == 'liter' || $satuanDatabase == 'l') {
            return 1.0; // Anggap 1 kg / 1 liter
        }

        if ($satuanDatabase == 'gr' || $satuanDatabase == 'gram') {
            return 0.1; // Asumsi 100gr jika tidak ditulis detail
        }

        // Jika satuannya pcs/pack, kembalikan 1
        return 1.0;
    }

    public function index(Request $request)
    {
        $inputBahan = $request->input('bahan');
        $budget = $request->input('budget');
        $porsi = $request->input('porsi');
        $kualitas = $request->input('kualitas');

        $rekomendasiList = [];
        $totalCost = 0;
        $statusRekomendasi = null; 
        $opsi1 = null; 
        $opsi2 = null; 
        $opsi3 = null; 

        if ($inputBahan && $budget && $porsi && $kualitas) {
            $barisBahan = preg_split('/[\n,]+/', $inputBahan);
            
            $itemsDipilih = [];
            $itemsSedang = [];
            $totalCostSedang = 0;

            foreach ($barisBahan as $bahan) {
                $bahan = trim($bahan);
                if (empty($bahan)) continue;

                // 1. Coba mapping ke Dataset Internal
                $bahanKey = strtolower($bahan);
                $datasetRule = ['base' => 'pcs', 'porsi' => 15]; // Default rule (unknown)
                foreach ($this->mlDataset as $key => $rule) {
                    if (str_contains($bahanKey, $key)) {
                        $datasetRule = $rule;
                        break;
                    }
                }

                $barangs = Barang::where('nama_barang', 'like', "%{$bahan}%")
                            ->where('stok', '>', 0)
                            ->get();

                if ($barangs->count() > 0) {
                    $termurah = $barangs->sortBy('harga')->first();
                    $termahal = $barangs->sortByDesc('harga')->first();

                    $itemPilihan = ($kualitas == 'tinggi') ? $termahal : $termurah;

                    // 2. Machine Learning Sizing Extraction (Menghitung Porsi Mampu per 1 Pcs Barang Asli)
                    $multiplierPilihan = $this->extractSizeMultiplier($itemPilihan->nama_barang, $itemPilihan->satuan, $datasetRule);
                    $kapasitasPorsiPilihan = max(1, $multiplierPilihan * $datasetRule['porsi']);
                    $qtyPerBahanPilihan = max(1, ceil($porsi / $kapasitasPorsiPilihan));

                    $multiplierSedang = $this->extractSizeMultiplier($termurah->nama_barang, $termurah->satuan, $datasetRule);
                    $kapasitasPorsiSedang = max(1, $multiplierSedang * $datasetRule['porsi']);
                    $qtyPerBahanSedang = max(1, ceil($porsi / $kapasitasPorsiSedang));

                    // Akumulasi harga
                    $totalCost += ($itemPilihan->harga * $qtyPerBahanPilihan);
                    
                    $itemsDipilih[] = [
                        'keyword' => $bahan,
                        'barang' => $itemPilihan,
                        'qty' => $qtyPerBahanPilihan,
                        'subtotal' => $itemPilihan->harga * $qtyPerBahanPilihan,
                        'rule' => $datasetRule,
                    ];

                    $totalCostSedang += ($termurah->harga * $qtyPerBahanSedang);
                    $itemsSedang[] = [
                        'keyword' => $bahan,
                        'barang' => $termurah,
                        'qty' => $qtyPerBahanSedang,
                        'subtotal' => $termurah->harga * $qtyPerBahanSedang,
                        'rule' => $datasetRule,
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

                    // Opsi 2: Porsi menyesuaikan budget (Akurasi Tinggi menggunakan while loop)
                    $rasio = $budget / $totalCost;
                    $porsiTebakan = floor($porsi * $rasio);
                    $porsiMampu = 0;
                    $costMampu = 0;
                    $itemsMampu = [];

                    while ($porsiTebakan > 0) {
                        $tempCost = 0;
                        $tempItems = [];
                        foreach ($itemsDipilih as $item) {
                            $multiplier = $this->extractSizeMultiplier($item['barang']->nama_barang, $item['barang']->satuan, $item['rule']);
                            $kapasitasPorsi = max(1, $multiplier * $item['rule']['porsi']);
                            $qty = max(1, ceil($porsiTebakan / $kapasitasPorsi));
                            
                            $sub = $item['barang']->harga * $qty;
                            $tempCost += $sub;
                            $tempItems[] = [
                                'keyword' => $item['keyword'],
                                'barang' => $item['barang'],
                                'qty' => $qty,
                                'subtotal' => $sub
                            ];
                        }
                        
                        if ($tempCost <= $budget) {
                            $costMampu = $tempCost;
                            $itemsMampu = $tempItems;
                            $porsiMampu = $porsiTebakan;
                            break;
                        }
                        $porsiTebakan--;
                    }

                    if ($porsiMampu > 0) {
                        $opsi2 = [
                            'judul' => 'Prioritas Budget (Porsi Dikurangi)',
                            'deskripsi' => 'Dengan budget Rp ' . number_format($budget, 0, ',', '.') . ', sistem AI kami menghitung maksimal cukup untuk sekitar ' . $porsiMampu . ' porsi (Kualitas ' . ucfirst($kualitas) . ').',
                            'budget_dibutuhkan' => $costMampu,
                            'items' => $itemsMampu
                        ];
                    }

                    // Opsi 3: Kualitas menyesuaikan budget
                    if ($kualitas == 'tinggi' && $totalCostSedang <= $budget) {
                        $opsi3 = [
                            'judul' => 'Kompromi Kualitas (Standar)',
                            'deskripsi' => 'Menurunkan kualitas bahan ke tingkat Standar. Porsi tetap ' . $porsi . ' porsi dan masuk dalam budget.',
                            'budget_dibutuhkan' => $totalCostSedang,
                            'items' => $itemsSedang
                        ];
                    } elseif ($kualitas == 'tinggi') {
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
