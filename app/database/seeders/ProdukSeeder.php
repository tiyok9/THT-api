<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::insert([
            'id' => '73d7fca2-1966-4d18-a247-b091cda060ee',
            'nama_produk' => 'produk',
            'harga_beli' => '120000',
            'harga_jual' => '220000',
            'stok' => '4',
            'img' => 'produk.jpg',
            'id_kategori' => '73d7fca2-1966-4d18-a247-b091cda06022',
        ]);
    }
}
