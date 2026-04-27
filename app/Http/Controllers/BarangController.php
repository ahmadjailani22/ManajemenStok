<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')
            ->orderBy('kode_barang')
            ->paginate(10);

        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'   => 'required|unique:barang,kode_barang',
            'nama_barang'   => 'required',
            'id_kategori'   => 'nullable|exists:kategori,id_kategori',
            'satuan'        => 'required',
            'harga_beli'    => 'required|numeric|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'stok_minimum'  => 'required|integer|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        Barang::create($request->except('_token'));

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    // Gunakan type-hint Barang $barang (Route Model Binding)
    public function show(Barang $barang)
    {
        $barang->load('kategori');
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang'   => 'required|unique:barang,kode_barang,' . $barang->id_barang . ',id_barang',
            'nama_barang'   => 'required',
            'id_kategori'   => 'nullable|exists:kategori,id_kategori',
            'satuan'        => 'required',
            'harga_beli'    => 'required|numeric|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'stok_minimum'  => 'required|integer|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $barang->update($request->except('_token', '_method'));

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->update(['status' => 'nonaktif']);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dinonaktifkan.');
    }
}