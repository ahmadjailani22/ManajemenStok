<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id('id_keluar');
            $table->string('kode_keluar', 20)->unique();
            $table->unsignedBigInteger('id_barang');
            $table->date('tanggal_keluar');
            $table->integer('jumlah');
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->string('keterangan', 255)->nullable();
            $table->unsignedBigInteger('id_user');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
