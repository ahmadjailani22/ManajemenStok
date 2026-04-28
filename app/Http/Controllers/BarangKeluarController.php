<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $search = request('search');

        $barangKeluar = BarangKeluar::with(['barang'])
            ->when($search, function ($query) use ($search) {
                $query->where('kode_transaksi', 'like', "%{$search}%") // fix: kode_keluar → kode_transaksi
                    ->orWhereHas('barang', fn($q) => $q->where('nama_barang', 'like', "%{$search}%"));
            })
            ->orderBy('tanggal_keluar', 'desc')
            ->orderBy('id_keluar', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('barang-keluar.index', compact('barangKeluar', 'search'));
    }

    public function create()
    {
        $kodeKeluar = BarangKeluar::generateKode();
        $barangs = Barang::where('status', 'aktif')
            ->where('stok_saat_ini', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        return view('barang-keluar.create', compact('kodeKeluar', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'tanggal_keluar' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'id_barang.required' => 'Barang wajib dipilih.',
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
        ]);

        $barang = Barang::findOrFail($request->id_barang);
        if ($barang->stok_saat_ini < $request->jumlah) {
            return back()->withInput()
                ->withErrors(['jumlah' => "Stok tidak cukup. Stok tersedia: {$barang->stok_saat_ini}"]);
        }

        DB::transaction(function () use ($request) {
            BarangKeluar::create([
                'kode_transaksi' => BarangKeluar::generateKode(), // fix: kode_keluar → kode_transaksi
                'id_barang' => $request->id_barang,
                'tanggal_keluar' => $request->tanggal_keluar,
                'jumlah' => $request->jumlah,
                'harga_jual_saat_ini' => $request->harga_jual,        // fix: harga_jual → harga_jual_saat_ini
                // total_harga DIHAPUS → kolom generated otomatis oleh DB
                'keterangan' => $request->keterangan,
                'id_user' => Auth::id(),
            ]);

            Barang::where('id_barang', $request->id_barang)
                ->decrement('stok_saat_ini', $request->jumlah);
        });

        return redirect()->route('barang-keluar.index')
            ->with('success', 'Transaksi barang keluar berhasil disimpan.');
    }

    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load(['barang.kategori', 'user']);
        return view('barang-keluar.show', compact('barangKeluar'));
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        DB::transaction(function () use ($barangKeluar) {
            Barang::where('id_barang', $barangKeluar->id_barang)
                ->increment('stok_saat_ini', $barangKeluar->jumlah);
            $barangKeluar->delete();
        });

        return redirect()->route('barang-keluar.index')
            ->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
    }
}