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
            'status' => 'active',
            'symbol' => "₺",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Currency::insert([
            'name' => 'USD',
            'status' => 'active',
            'symbol' => "$",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Currency::insert([
            'name' => 'Euro',
            'status' => 'active',
            'symbol' => "€",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
