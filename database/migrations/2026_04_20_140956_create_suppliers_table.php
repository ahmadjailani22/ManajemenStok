<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'telepon',
        'email',
        'status',
    ];

    /**
     * Generate kode supplier otomatis format SUP-XXX
     */
    public static function generateKode(): string
    {
        $last = self::orderBy('id', 'desc')->first();

        if (!$last) {
            return 'SUP-001';
        }

        $lastNumber = (int) substr($last->kode_supplier, 4);
        $nextNumber = $lastNumber + 1;

        return 'SUP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope: hanya supplier aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Helper: apakah supplier aktif?
     */
    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
