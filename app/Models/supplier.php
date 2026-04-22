<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'telepon',
        'email',
    ];

    /**
     * Generate kode supplier otomatis dengan format SUP-XXX
     */
    public static function generateKode(): string
    {
        $last = self::orderBy('id', 'desc')->first();

        if (!$last) {
            return 'SUP-001';
        }

        // Ambil angka dari kode terakhir, misal SUP-007 => 7
        $lastNumber = (int) substr($last->kode_supplier, 4);
        $nextNumber = $lastNumber + 1;

        return 'SUP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
