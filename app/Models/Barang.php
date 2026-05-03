<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table      = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing  = true;
    protected $keyType    = 'int';

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

    public function getRouteKeyName()
    {
        return 'id_barang';
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function statusStok(): string
    {
        if ($this->stok_saat_ini == 0) return 'habis';
        if ($this->stok_saat_ini <= $this->stok_minimum) return 'menipis';
        return 'aman';
    }
}