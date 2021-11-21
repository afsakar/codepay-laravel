<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::insert([
            'name' => 'Azad Furkan Şakar',
            'email' => 'afsakarr@gmail.com',
            'role_id' => 1,
            'permissions' => "null",
            'email_verified_at' => now(),
            'password' => '$2y$10$ytNS/s.CSs9buM5iMFlsIOBIE/6Vjvv17Opb3Jl8rg/KoTy.DKs0.',
            'remember_token' => Str::random(10),
        ]);
    }
}
