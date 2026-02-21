<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        // Mengambil data barang beserta relasi kategorinya (Eager Loading)
        $barangs = Barang::with('kategori')->latest()->get();
        return view('admin.barang.index', compact('barangs'));
    }

    public function create()
    {
        // Lempar data kategori untuk pilihan dropdown
        $kategoris = Kategori::all();
        return view('admin.barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_barang' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ]);

        $data = $request->all();

        // Proses upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('barangs', 'public');
            $data['gambar'] = $gambarPath;
        }

        Barang::create($data);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return view('admin.barang.edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_barang' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Cek jika ada upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            // Upload gambar baru
            $gambarPath = $request->file('gambar')->store('barangs', 'public');
            $data['gambar'] = $gambarPath;
        }

        $barang->update($data);

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Barang $barang)
    {
        // Hapus file gambar dari storage sebelum menghapus data dari database
        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }
        
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}