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
<<<<<<< HEAD
=======
            'created_at' => now(),
            'updated_at' => now(),
>>>>>>> origin/develop
        ]);

        Role::create([
<<<<<<< HEAD
            'name' => 'admin rg',
=======
            'name' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
>>>>>>> origin/develop
        ]);
    }
}
