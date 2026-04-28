<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';
    protected $primaryKey = 'id_keluar';
    public $timestamps = false; // ← TAMBAHKAN INI

    protected $fillable = [
        'kode_transaksi',
        'id_barang',
        'id_user',
        'jumlah',
        'harga_jual_saat_ini',
        'tanggal_keluar',
        'customer',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_keluar' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public static function generateKode()
    {
        $last = self::orderBy('id_keluar', 'desc')->first();
        $no = $last ? ((int) substr($last->kode_transaksi, -3)) + 1 : 1;
        return 'TRX-OUT-' . str_pad($no, 3, '0', STR_PAD_LEFT);
    }
}