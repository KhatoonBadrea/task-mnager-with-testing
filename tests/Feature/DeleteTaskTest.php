<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Faker\Factory as FakerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteTaskTest extends TestCase
{
    use DatabaseTransactions;
    protected $taskService;
    protected $faker;
    protected function setUp(): void
    {
        parent::setUp();
        $this->taskService = new TaskService();
        $this->faker = FakerFactory::create(); 

    }

    public function test_authorized_user_can_delete_task()
    {
        $adminRole = Role::where('name', 'Admin')->first();

        $adminUser = User::factory()->create([
            'role_id' => $adminRole->id,
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $token = auth()->login($adminUser);
        
        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'This is a test description.',
            'type' => 'Bug',
            'priority' => 'low',
            'assigned_to' => $adminUser->id,
            'created_by' => $adminUser->id,
        ]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->delete(route('tasks.destroy', $task->id));

        $response->assertStatus(200);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);    }

    public function test_unauthorized_user_cannot_delete_task()
    {
        $devRole = Role::where('name', 'Developer')->firstOrCreate(['name' => 'Developer','description'=>'kkkkkkkk']);
        $devUser = User::factory()->create(['role_id' => $devRole->id]);

        $token = auth()->login($devUser);

        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'This is a test description.',
            'type' => 'Bug',
            'priority' => 'low',
            'assigned_to' => $devUser->id,
            'created_by' => $devUser->id,
        ]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->delete(route('tasks.destroy', $task->id));

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}