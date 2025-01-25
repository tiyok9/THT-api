<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('img');
            $table->string('nama_produk');
            $table->double('harga_beli');
            $table->double('harga_jual');
            $table->integer('stok');
            $table->foreignUuid('id_kategori')->onDelete('restrict')->constrained('kategori');
            $table->boolean('safe_delete')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
