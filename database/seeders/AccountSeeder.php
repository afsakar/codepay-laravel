<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Account::insert([
            'name' => 'Ziraat Bankası',
            'account_type_id' => 1,
            'owner' => 'Tire İnşaat',
            'description' => 'Bank Account',
            'balance' => '6580.500',
            'status' => 'active',
            'currency_id' => 1,
            'currency_status' => 'after',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Account::insert([
            'name' => 'Halkbank',
            'account_type_id' => 1,
            'owner' => 'Mehmet Ali Oruç',
            'description' => 'Bank Account',
            'balance' => '254.960',
            'status' => 'active',
            'currency_id' => 1,
            'currency_status' => 'after',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Account::insert([
            'name' => 'QNB Finansbank',
            'account_type_id' => 2,
            'owner' => 'Azad Furkan Şakar',
            'description' => 'Bank Account',
            'balance' => '1500.850',
            'status' => 'inactive',
            'currency_id' => 2,
            'currency_status' => 'before',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Account::insert([
            'name' => 'Kuveyt Turk',
            'account_type_id' => 1,
            'owner' => 'Tire İnşaat',
            'description' => 'Bank Account',
            'balance' => '0.000',
            'status' => 'active',
            'currency_id' => 1,
            'currency_status' => 'after',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Account::insert([
            'name' => 'Vakıfbank',
            'account_type_id' => 1,
            'owner' => 'Azad Furkan Şakar',
            'description' => 'Bank Account',
            'balance' => '550.890',
            'status' => 'inactive',
            'currency_id' => 3,
            'currency_status' => 'after',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
