<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'role_id' => 1,
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Irfan',
            'email' => 'irfan@gmail.com',
            'role_id' => 2,
            'password' => bcrypt('12345678'),
        ]);
        User::create([
            'name' => 'Farida',
            'email' => 'farida@gmail.com',
            'role_id' => 2,
            'password' => bcrypt('12345678'),
        ]);
        User::create([
            'name' => 'Wahid',
            'email' => 'wahid@gmail.com',
            'role_id' => 2,
            'password' => bcrypt('12345678'),
        ]);
        User::create([
            'name' => 'Reza',
            'email' => 'reza@gmail.com',
            'role_id' => 2,
            'password' => bcrypt('12345678'),
        ]);
    }
}
