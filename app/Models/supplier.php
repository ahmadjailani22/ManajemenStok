<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'telepon',
        'email',
        'status',       // ← pastikan 'status' ada di sini
    ];

    public static function generateKode(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        if (!$last) return 'SUP-001';
        $lastNumber = (int) substr($last->kode_supplier, 4);
        return 'SUP-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
