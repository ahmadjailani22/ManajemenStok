<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $search       = $request->get('search');
        $filterKat    = $request->get('kategori');
        $filterStatus = $request->get('status');

        $barang = Barang::with('kategori')
            ->when($search, function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%");
            })
            ->when($filterKat, function ($q) use ($filterKat) {
                $q->where('id_kategori', $filterKat);
            })
            ->when($filterStatus, function ($q) use ($filterStatus) {
                if ($filterStatus === 'menipis') {
                    $q->whereRaw('stok_saat_ini <= stok_minimum');
                } elseif ($filterStatus === 'aman') {
                    $q->whereRaw('stok_saat_ini > stok_minimum');
                } elseif ($filterStatus === 'habis') {
                    $q->where('stok_saat_ini', 0);
                }
            })
            ->orderBy('kode_barang')
            ->paginate(15)
            ->withQueryString();

        $kategoris     = Kategori::orderBy('nama_kategori')->get();
        $totalBarang   = Barang::count();
        $totalMenipis  = Barang::whereRaw('stok_saat_ini <= stok_minimum')->count();
        $totalHabis    = Barang::where('stok_saat_ini', 0)->count();
        $totalAman     = Barang::whereRaw('stok_saat_ini > stok_minimum')->count();

        return view('laporan.stok', compact(
            'barang', 'kategoris', 'search',
            'filterKat', 'filterStatus',
            'totalBarang', 'totalMenipis', 'totalHabis', 'totalAman'
        ));
    }
}