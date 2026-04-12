<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table      = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'kode_barang',
        'barcode',
        'nama_barang',
        'id_kategori',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok_minimum',
        'stok_saat_ini',
        'letak_rak',
        'gambar',
        'status',
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}