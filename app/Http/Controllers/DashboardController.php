<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data dari view v_dashboard_summary
        $summary = DB::table('v_dashboard_summary')->first();

        // Ambil stok menipis
        $stok_menipis = DB::table('barang')
            ->leftJoin('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori')
            ->select('barang.kode_barang', 'barang.nama_barang', 'barang.stok_saat_ini', 'barang.stok_minimum')
            ->whereRaw('barang.stok_saat_ini <= barang.stok_minimum')
            ->where('barang.status', 'aktif')
            ->orderByRaw('barang.stok_saat_ini ASC')
            ->limit(5)
            ->get();

        // Ambil transaksi terakhir (gabungan masuk & keluar)
        $masuk = DB::table('barang_masuk')
            ->join('barang', 'barang_masuk.id_barang', '=', 'barang.id_barang')
            ->select(
                DB::raw("'MASUK' as tipe"),
                'barang_masuk.kode_transaksi',
                'barang.nama_barang',
                'barang_masuk.jumlah',
                'barang_masuk.tanggal_masuk as tanggal'
            );
            
        $transaksi_terakhir = DB::table('barang_keluar')
            ->join('barang', 'barang_keluar.id_barang', '=', 'barang.id_barang')
            ->select(
                DB::raw("'KELUAR' as tipe"),
                'barang_keluar.kode_transaksi',
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