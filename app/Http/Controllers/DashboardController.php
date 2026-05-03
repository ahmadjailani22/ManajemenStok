<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary cards — query langsung tanpa view database
        $summary = (object) [
            'total_barang' => DB::table('barang')
                ->where('status', 'aktif')
                ->count(),

            'stok_menipis' => DB::table('barang')
                ->whereRaw('stok_saat_ini <= stok_minimum')
                ->where('status', 'aktif')
                ->count(),

            'transaksi_masuk_hari_ini' => DB::table('barang_masuk')
                ->whereDate('tanggal_masuk', today())
                ->count(),

            'transaksi_keluar_hari_ini' => DB::table('barang_keluar')
                ->whereDate('tanggal_keluar', today())
                ->count(),
        ];

        // Stok menipis detail
        $stok_menipis = DB::table('barang')
            ->leftJoin('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori')
            ->select(
                'barang.kode_barang',
                'barang.nama_barang',
                'barang.stok_saat_ini',
                'barang.stok_minimum'
            )
            ->whereRaw('barang.stok_saat_ini <= barang.stok_minimum')
            ->where('barang.status', 'aktif')
            ->orderBy('barang.stok_saat_ini', 'asc')
            ->limit(5)
            ->get();

        // Transaksi terakhir — gunakan kode_transaksi (nama kolom yang benar)
        $masuk = DB::table('barang_masuk')
            ->join('barang', 'barang_masuk.id_barang', '=', 'barang.id_barang')
            ->select(
                DB::raw("'MASUK' as tipe"),
                'barang_masuk.kode_transaksi', // ← perbaikan: bukan kode_masuk
                'barang.nama_barang',
                'barang_masuk.jumlah',
                'barang_masuk.tanggal_masuk as tanggal'
            );

        $transaksi_terakhir = DB::table('barang_keluar')
            ->join('barang', 'barang_keluar.id_barang', '=', 'barang.id_barang')
            ->select(
                DB::raw("'KELUAR' as tipe"),
                'barang_keluar.kode_transaksi', // ← perbaikan: bukan kode_keluar
                'barang.nama_barang',
                'barang_keluar.jumlah',
                'barang_keluar.tanggal_keluar as tanggal'
            )
            ->union($masuk)
            ->orderBy('tanggal', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard', compact('summary', 'stok_menipis', 'transaksi_terakhir'));
    }
}