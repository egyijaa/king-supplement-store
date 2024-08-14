<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('kings99@@')
        ]);
        // create kasir
        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@gmail.com',
            'role' => 'cashier',
            'password' => bcrypt('kings99@@'),
        ]);
    }
}
