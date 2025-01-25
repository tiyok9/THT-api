<?php

namespace Tests\Feature;

use Database\Seeders\AkunSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AkunSeeder::class]);
        $accessToken = parent::login();
        $this->withHeader('Authorization', 'Bearer ' . $accessToken);
    }

    public function testLogin(): void
    {
        $this->post("/api/login",[
            'email' => 'pras@gmail.com',
            'password' => 'prasetyo7'
        ])->assertStatus(200);
    }
    public function testRegister(): void
    {
        $this->post('/api/register',[
            'name' => 'users',
            'email' => 'user@gmail.com',
            'password' => '123456',
            'posisi' => 'web',
        ])->assertJson([
            'message' => true
        ]) ->assertStatus(201);
    }

}
