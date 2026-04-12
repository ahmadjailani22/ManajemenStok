<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Tampilkan daftar barang
    public function index()
    {
        $barang = Barang::with('kategori')
            ->orderBy('kode_barang')
            ->paginate(10);

        return view('barang.index', compact('barang'));
    }

    // Form tambah barang
    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('barang.create', compact('kategori'));
    }

    // Simpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'  => 'required|unique:barang,kode_barang',
            'nama_barang'  => 'required',
            'id_kategori'  => 'nullable|exists:kategori,id_kategori',
            'satuan'       => 'required',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'stok_saat_ini'=> 'required|integer|min:0',
            'letak_rak'    => 'nullable',
            'status'       => 'required|in:aktif,nonaktif',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique'   => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'satuan.required'      => 'Satuan wajib diisi.',
            'harga_beli.required'  => 'Harga beli wajib diisi.',
            'harga_jual.required'  => 'Harga jual wajib diisi.',
            'stok_minimum.required'=> 'Stok minimum wajib diisi.',
            'stok_saat_ini.required'=> 'Stok saat ini wajib diisi.',
        ]);

        Barang::create($request->except('_token'));

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    // Form edit barang
    public function edit($id)
    {
        $barang   = Barang::findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    // Update barang
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'kode_barang'  => 'required|unique:barang,kode_barang,' . $id . ',id_barang',
            'nama_barang'  => 'required',
            'id_kategori'  => 'nullable|exists:kategori,id_kategori',
            'satuan'       => 'required',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'stok_saat_ini'=> 'required|integer|min:0',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $barang->update($request->except('_token', '_method'));

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->update(['status' => 'nonaktif']);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dinonaktifkan.');
    }

    // Detail barang
    public function show($id)
    {
        $barang = Barang::with('kategori')->findOrFail($id);
        return view('barang.show', compact('barang'));
    }
}