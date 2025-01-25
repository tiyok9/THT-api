<?php

namespace Tests\Feature;

use App\Models\Produk;
use Database\Seeders\KategoriSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\AkunSeeder;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProdukTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AkunSeeder::class]);
        $accessToken = parent::login();
        $this->withHeader('Authorization', 'Bearer ' . $accessToken);
        $this->seed([KategoriSeeder::class]);
        $this->seed([ProdukSeeder::class]);

    }
    public function testGetProduk()
    {
        $this->get('/api/produk')
            ->assertStatus(200)
            ->assertJson([
                'data'=>[
                    [
                       "id"=> "73d7fca2-1966-4d18-a247-b091cda060ee",
                        "img"=> "produk.jpg",
                        "nama_produk"=> "produk",
                        "harga_beli"=> "120000",
                        "harga_jual"=> "220000",
                        "stok"=> 4,
                        "nama_kategori"=> "kategori"
                    ]
                ]
            ]);
    }
    public function testPostProduct()
    {
        Storage::fake('produk');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response= $this->post('/api/produk/store',[
            'nama_produk' => 'produk',
            'img' => $file,
            'harga_beli' => '120000',
            "stok"=> 4,
            "id_kategori"=> '73d7fca2-1966-4d18-a247-b091cda06022',
        ])->assertStatus(201);

    }

    public function testUpdateProduct()
    {
        Storage::fake('produk');
        $file =  UploadedFile::fake()->image('avatar.jpg');
        $this->patch('/api/produk/update/73d7fca2-1966-4d18-a247-b091cda060ee',[
            'nama_produk' => 'produk2',
            'img' => $file,
            'harga_beli' => '120000',
            "stok"=> 4,
            "id_kategori"=> '73d7fca2-1966-4d18-a247-b091cda06022',
        ])->assertJson([
            'message' => true
        ]) ->assertStatus(201);

    }

    public function testShowProduk()
    {
        $this->get('/api/produk/73d7fca2-1966-4d18-a247-b091cda060ee')
            ->assertStatus(200)
            ->assertJson([
                'data'=>[
                    "id"=> "73d7fca2-1966-4d18-a247-b091cda060ee",
                    "img"=> "produk.jpg",
                    "nama_produk"=> "produk",
                    "harga_beli"=> "120000",
                    "harga_jual"=> "220000",
                    "stok"=> 4,
                    "id_kategori"=> "73d7fca2-1966-4d18-a247-b091cda06022"
                ]
            ]);
    }

    public function testDeleteCategory()
    {

        $this->delete('/api/produk/73d7fca2-1966-4d18-a247-b091cda060ee')
            ->assertStatus(201)
            ->assertJson(
                [
                    'message' => true
                ]
            );
    }
}
