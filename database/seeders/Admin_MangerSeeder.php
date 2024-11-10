<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Admin_MangerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role_id' => 1,
            'password' => '12345678',
        ]);
        User::create([
            'name' => 'manager1',
            'email' => 'manager1@gmail.com',
            'role_id' => 2,
            'password' => '12345678',
        ]);
        User::create([
            'name' => 'manager2',
            'email' => 'manager2@gmail.com',
            'role_id' => 2,
            'password' => '12345678',
        ]);
        User::create([
            'name' => 'developer',
            'email' => 'developer@gmail.com',
            'role_id' => 3,
            'password' => '12345678',
        ]);
    }
}
