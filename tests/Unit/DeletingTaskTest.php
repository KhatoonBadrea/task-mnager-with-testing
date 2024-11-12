<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletingTaskTest extends TestCase
{
    use WithFaker;

    public function test_authorized_user_can_delete_task()
    {
        // إنشاء الدور "Admin"
        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Admin role']);

        // إنشاء المستخدم مع تعيين الدور
        $adminUser = User::factory()->create([
            'role_id' => $adminRole->id,
            'name' => 'Admin User'
        ]);

        // محاكاة تسجيل دخول المستخدم باستخدام `actingAs`
        $token = Auth::guard('api')->login($adminUser);

        // إنشاء مهمة حقيقية
        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'This is a test description.',
            'type' => 'Bug',
            'priority' => 'low',
            'assigned_to' => $adminUser->id,
            'depends_on' => null,
            'created_by' => $adminUser->id,
        ]);
        // إرسال طلب حذف للمهمة
        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertStatus(200);
        $this->assertSoftDeleted($task);
    }

    public function test_unauthorized_user_cannot_delete_task()
    {
        // إنشاء الدور "Developer"
        $devRole = Role::create(['name' => 'Developer', 'description' => 'Developer role']);

        // إنشاء المستخدم مع تعيين الدور
        $devUser = User::factory()->create([
            'role_id' => $devRole->id,
            'name' => 'Developer User'
        ]);

        // محاكاة تسجيل دخول المستخدم باستخدام `actingAs`
        $token = Auth::guard('api')->login($devUser);

        // إنشاء مهمة حقيقية
        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'This is a test description.',
            'type' => 'Bug',
            'priority' => 'low',
            'assigned_to' => $devUser->id,
            'depends_on' => null,
            'created_by' => $devUser->id,
        ]);
        // إرسال طلب حذف للمهمة
        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $task->id]); // تأكد من أن المهمة لم تُحذف
    }
}
