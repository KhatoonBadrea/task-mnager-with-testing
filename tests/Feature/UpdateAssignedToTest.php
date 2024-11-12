<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Faker\Factory as FakerFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateAssignedToTest  extends TestCase
{
    use DatabaseTransactions;
    protected $taskService;
    protected $faker;
    protected function setUp(): void
    {
        parent::setUp();
        $this->taskService = new TaskService();
        $this->faker = FakerFactory::create(); // إنشاء كائن Faker

    }

    public function test_update_task_successfully()
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $devRole = Role::where('name', 'Developer')->first();

        $adminUser = User::factory()->create([
            'role_id' => $adminRole->id,
            'email' => $this->faker->unique()->safeEmail,
        ]);
        $devUser = User::factory()->create([
            'role_id' => $devRole->id,
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $token = JWTAuth::fromUser($adminUser);

        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'This is a test description.',
            'type' => 'Bug',
            'priority' => 'low',
            'assigned_to' => $adminUser->id,
            'depends_on' => null,
            'created_by' => $adminUser->id,
        ]);

        $taskData = [
            'assigned_to' => $devUser->id,

        ];

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->putJson("/api/tasks/{$task->id}/assigne", $taskData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Test Task',
            'description' => 'This is a test description.',
            'type' => 'Bug',
            'priority' => 'low',
            'assigned_to' => $devUser->id,
            'depends_on' => null,
            'created_by' => $adminUser->id,
        ]);
    }



    public function test_update_task_not_found()
    {
        $user = User::factory()->create();
        JWTAuth::setToken(JWTAuth::fromUser($user));

        $task = new Task();

        $result = $this->taskService->update_task($task, []);

        $this->assertFalse($result);
    }
}
