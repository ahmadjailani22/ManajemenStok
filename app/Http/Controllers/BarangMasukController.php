<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $search = request('search');

        $barangMasuk = BarangMasuk::with(['barang', 'supplier'])
            ->when($search, function ($query) use ($search) {
                $query->where('kode_masuk', 'like', "%{$search}%")
                    ->orWhereHas('barang', fn($q) => $q->where('nama_barang', 'like', "%{$search}%"))
                    ->orWhereHas('supplier', fn($q) => $q->where('nama_supplier', 'like', "%{$search}%"));
            })
            ->orderBy('tanggal_masuk', 'desc')
            ->orderBy('id_masuk', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('barang-masuk.index', compact('barangMasuk', 'search'));
    }

    public function create()
    {
        $kodeMasuk = BarangMasuk::generateKode(); // hanya untuk preview di form
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        $suppliers = Supplier::where('status', 'aktif')->orderBy('nama_supplier')->get();

        return view('barang-masuk.create', compact('kodeMasuk', 'barangs', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'tanggal_masuk' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'id_barang.required' => 'Barang wajib dipilih.',
            'id_supplier.required' => 'Supplier wajib dipilih.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Generate kode baru saat benar-benar disimpan
                $kode = BarangMasuk::generateKode();

                BarangMasuk::create([
                    'kode_masuk' => $kode,
                    'id_barang' => $request->id_barang,
                    'id_supplier' => $request->id_supplier,
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'jumlah' => $request->jumlah,
                    'harga_beli_saat_ini' => $request->harga_beli,
                    'keterangan' => $request->keterangan,
                    'id_user' => Auth::id(),
                ]);

                Barang::where('id_barang', $request->id_barang)
                    ->increment('stok_saat_ini', $request->jumlah);
            });
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
        }

        return redirect()->route('barang-masuk.index')
            ->with('success', 'Transaksi barang masuk berhasil disimpan.');
    }

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load(['barang.kategori', 'supplier', 'user']);
        return view('barang-masuk.show', compact('barangMasuk'));
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        try {
            DB::transaction(function () use ($barangMasuk) {
                Barang::where('id_barang', $barangMasuk->id_barang)
                    ->decrement('stok_saat_ini', $barangMasuk->jumlah);
                $barangMasuk->delete();
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
        }

        return redirect()->route('barang-masuk.index')
            ->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
    }
}