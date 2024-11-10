<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Services\TaskService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatingTaskTest extends TestCase
{
    // use RefreshDatabase;

    protected TaskService $TaskService;

    protected function setUp(): void
    {
        parent::setUp();
    
        // Seed roles explicitly before each test
        // $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }
    
    public function test_create_task_success()
    {
        $adminRole = Role::where('name', 'Admin')->first();
    
        $adminUser = User::factory()->create([
            'role_id' => $adminRole->id,
            'email' => 'admin'.random_int(0,9).'@gmail.com',
        ]);
    
        $token = JWTAuth::fromUser($adminUser);
    
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test description.',
            'type' => 'Bug',
            'priority' => 'low',
            'assigned_to' => $adminUser->id,
            'depends_on' => null,
        ];
    
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->postJson('/api/tasks', $taskData);
    
        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
        $this->assertFalse(Cache::has('tasks'));
    }
    

    public function test_create_task_failure_due_to_missing_data()
    {
        // Authenticate an admin user
        $adminUser = User::factory()->create();
        $token = JWTAuth::fromUser($adminUser);

        // Incomplete task data
        $taskData = [
            'title' => '',
            'description' => '',
            'type' => '',
            'priority' => '',
            'assigned_to' => null,
        ];

        // Attempt to create a task with invalid data
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/tasks', $taskData);

        // Assert validation failure or error handling
        $response->assertStatus(403);
    }
}
