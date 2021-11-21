<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::insert([
            'name' => 'Super Admin',
            'description' => 'Super Admin can do whatever want!',
            'permissions' => "null",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \App\Models\Role::insert([
            'name' => 'Admin',
            'description' => 'Admin can do whatever Super Admin want!',
            'permissions' => "null",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \App\Models\Role::insert([
            'name' => 'User',
            'description' => 'User can do something!',
            'permissions' => "null",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
