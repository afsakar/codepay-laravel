<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Currency::insert([
            'name' => 'Turkish Lira',
            'code' => 'TRY',
            'status' => 'active',
            'symbol' => "₺",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Currency::insert([
            'name' => 'American Dollar',
            'code' => 'USD',
            'status' => 'active',
            'symbol' => "$",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Currency::insert([
            'name' => 'Euro',
            'code' => 'EUR',
            'status' => 'active',
            'symbol' => "€",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Currency::insert([
            'name' => 'Pound Sterling',
            'code' => 'GBP',
            'status' => 'active',
            'symbol' => "£",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
