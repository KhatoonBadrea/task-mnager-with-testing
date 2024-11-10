<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name'=>'Admin',
            'description'=>'System manager',
        ]);
        Role::create([
            'name'=>'Manager',
            'description'=>'manage some operation',
        ]);
        Role::create([
            'name'=>'Developer',
            'description'=>'Do the task',
        ]);
    }
}
