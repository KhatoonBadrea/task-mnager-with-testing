<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * تحديد من يمكنه إنشاء المهام.
     */
    public function create(User $user)
    {
        // السماح للمدير أو المدير العام فقط بإنشاء المهام
        return $user->role->id == 1 || $user->role->id == 2;
    }

    /**
     * تحديد من يمكنه تعديل المهام.
     */
    public function update(User $user, Task $task)
    {
        // السماح للمدير أو المدير العام بتعديل المهام التي أنشأوها فقط
        return ($user->role->id == 1 || $user->role->id == 2) && $user->id === $task->created_by;
    }

    /**
     * تحديد من يمكنه حذف المهام.
     */
    public function delete(User $user, Task $task)
    {
        // السماح للمدير أو المدير العام بحذف المهام التي أنشأوها فقط
        return ($user->role->id == 1 || $user->role->id == 2) && $user->id === $task->created_by;
    }
}
