<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function stok(Request $request)
    {
        $search      = $request->get('search');
        $filterKat   = $request->get('kategori');
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

    public function exportStokExcel(Request $request)
    {
        $barang = Barang::with('kategori')
            ->orderBy('kode_barang')
            ->get();

        $filename = 'laporan_stok_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($barang) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Kode Barang', 'Nama Barang', 'Kategori', 'Satuan',
                'Harga Beli', 'Harga Jual', 'Stok Minimum',
                'Stok Saat Ini', 'Status Stok', 'Letak Rak'
            ]);

            foreach ($barang as $b) {
                if ($b->stok_saat_ini == 0) {
                    $statusStok = 'Habis';
                } elseif ($b->stok_saat_ini <= $b->stok_minimum) {
                    $statusStok = 'Menipis';
                } else {
                    $statusStok = 'Aman';
                }

                fputcsv($file, [
                    $b->kode_barang,
                    $b->nama_barang,
                    $b->kategori->nama_kategori ?? '-',
                    $b->satuan,
                    $b->harga_beli,
                    $b->harga_jual,
                    $b->stok_minimum,
                    $b->stok_saat_ini,
                    $statusStok,
                    $b->letak_rak ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportStokPdf(Request $request)
    {
        $barang = Barang::with('kategori')->orderBy('kode_barang')->get();
        return view('laporan.export-stok-pdf', compact('barang'));
    }
}
