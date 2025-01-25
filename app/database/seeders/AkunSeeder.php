<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Core\Uuid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'id' => '73d7fca2-1966-4d18-a247-b091cda060bb',
            'name' => 'pras',
            'password' => Hash::make('prasetyo7'),
            'email' => 'pras@gmail.com',
            'posisi' => 'programmer',
        ]);
    }
}
