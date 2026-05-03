<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';
    protected $primaryKey = 'id_masuk';
    public $timestamps = false; // ← tabel tidak punya updated_at

    protected $fillable = [
        'kode_masuk',      // bukan kode_masuk
        'id_barang',
        'id_supplier',
        'id_user',
        'jumlah',
        'harga_beli_saat_ini', // bukan harga_beli
        'tanggal_masuk',
        'keterangan',
        // total_harga TIDAK dimasukkan → generated column otomatis
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public static function generateKode()
    {
        $last = self::orderBy('id_masuk', 'desc')->first();
        $no = $last ? ((int) substr($last->kode_masuk, -3)) + 1 : 1;
        return 'TRX-IN-' . str_pad($no, 3, '0', STR_PAD_LEFT);
    }
}