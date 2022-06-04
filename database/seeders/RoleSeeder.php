<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Role::create([
            'name' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
