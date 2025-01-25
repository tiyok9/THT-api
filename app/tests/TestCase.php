<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from users");
        DB::delete("delete from produk");
        DB::delete("delete from kategori");

    }

    protected function login()
    {
        $email = "pras@gmail.com";
        $password = "prasetyo7";

        $response = $this->postJson("/api/login", [
            "email" => $email,
            "password" => $password
        ]);

        $accessToken = $response->json('access_token');
        return $accessToken;
    }
}
