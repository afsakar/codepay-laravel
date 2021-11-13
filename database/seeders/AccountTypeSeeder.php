<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\AccountType::insert([
            'name' => 'Bank Account',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\AccountType::insert([
            'name' => 'Currency Account',
            'status' => 'inactive',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\AccountType::insert([
            'name' => 'Credit Card',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
