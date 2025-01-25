<?php

namespace Tests\Feature;

use Database\Seeders\AkunSeeder;
use Database\Seeders\KategoriSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KategoriTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AkunSeeder::class]);

        $accessToken = parent::login();
        $this->withHeader('Authorization', 'Bearer ' . $accessToken);
        $this->seed([KategoriSeeder::class]);
    }

    public function testGetCategory()
    {
        $this->get('/api/kategori')
            ->assertStatus(200)
            ->assertJson([
                'data'=>[
                    [
                        "id"=> "73d7fca2-1966-4d18-a247-b091cda06022",
                        "nama_kategori"=> "kategori"
                    ]
                ]
            ]);
    }

    public function testPostCategory()
    {

        $this->post('/api/kategori/store',[
            "nama_kategori" => "kategori2",
        ])
            ->assertStatus(201);

    }

    public function testUpdateCategory()
    {

        $this->patch('/api/kategori/update/73d7fca2-1966-4d18-a247-b091cda06022',[
            "nama_kategori" => "kategoriupdate",
        ])
            ->assertStatus(201)
            ->assertJson(
                [
                    'message' => true
                ]
            );
    }

    public function testShowCategory()
    {
        $this->get('/api/kategori/73d7fca2-1966-4d18-a247-b091cda06022')
            ->assertStatus(200)
            ->assertJson([
                'data'=>[
                        "id"=> "73d7fca2-1966-4d18-a247-b091cda06022",
                        "nama_kategori"=> "kategori"
                ]
            ]);
    }

    public function testDeleteCategory()
    {

        $this->delete('/api/kategori/73d7fca2-1966-4d18-a247-b091cda06022')
            ->assertStatus(201)
            ->assertJson(
                [
                    'message' => true
                ]
            );
    }
}
