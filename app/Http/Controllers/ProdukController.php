<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->orderByDesc('id')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return inertia('admin/produk/Index', [
            'produks' => $produks,
            'kategoris' => $kategoris,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:200'],
            'kategori_id' => ['required', 'integer', 'exists:kategori,id'],
            'harga' => ['required', 'numeric', 'min:0'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'link_gambar' => ['nullable', 'string', 'max:500'],
            'deskripsi' => ['nullable', 'string'],
            'stok' => ['nullable', 'integer', 'min:0'],
        ]);

        Produk::create($validated);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:200'],
            'kategori_id' => ['required', 'integer', 'exists:kategori,id'],
            'harga' => ['required', 'numeric', 'min:0'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'link_gambar' => ['nullable', 'string', 'max:500'],
            'deskripsi' => ['nullable', 'string'],
            'stok' => ['nullable', 'integer', 'min:0'],
        ]);

        $produk->update($validated);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
