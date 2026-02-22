<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    // Menampilkan daftar pelanggan
    public function index(Request $request)
    {
        // Ambil user dengan role 'pelanggan', tambahkan fitur pencarian jika butuh
        $query = User::where('role', 'pelanggan')->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $pelanggans = $query->paginate(10);
        
        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    // Menampilkan form edit pelanggan
    public function edit(User $pelanggan)
    {
        // Pastikan admin tidak mengedit sesama admin lewat URL ini
        if ($pelanggan->role !== 'pelanggan') {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    // Menyimpan update data pelanggan
    public function update(Request $request, User $pelanggan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pelanggan->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $pelanggan->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    // Menghapus pelanggan
    public function destroy(User $pelanggan)
    {
        if ($pelanggan->role !== 'pelanggan') {
            abort(403);
        }

        $pelanggan->delete();
        return redirect()->route('admin.pelanggan.index')->with('success', 'Akun pelanggan berhasil dihapus.');
    }
}