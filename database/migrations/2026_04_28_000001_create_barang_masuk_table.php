<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id('id_masuk');
            $table->string('kode_masuk', 20)->unique();
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_supplier');
            $table->date('tanggal_masuk');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->string('keterangan', 255)->nullable();
            $table->unsignedBigInteger('id_user');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};
