<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create([
            'title' => 'A',
            'description' => 'something',
            'type' => 'Feature',
            'status' => 'Open',
            'priority' => 'height',
            'created_by' => 1,
            'assigned_to' => 4,
            'depends_on' => null,
        ]);
        Task::create([
            'title' => 'B',
            'description' => 'something',
            'type' => 'Feature',
            'status' => 'Blocked',
            'priority' => 'height',
            'created_by' => 1,
            'assigned_to' => 4,
            'depends_on' => 1,
        ]);
        Task::create([
            'title' => 'C',
            'description' => 'something',
            'type' => 'Feature',
            'status' => 'Blocked',
            'priority' => 'height',
            'created_by' => 1,
            'assigned_to' => 4,
            'depends_on' => 1,
        ]);
    }
}
