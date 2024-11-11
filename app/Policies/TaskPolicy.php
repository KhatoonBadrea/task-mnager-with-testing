<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
   
    public function create(User $user)
    {
        return $user->role->id == 1 || $user->role->id == 2;
    }

  
    public function update(User $user, Task $task)
    {
        return ($user->role->id == 1 || $user->role->id == 2) && $user->id === $task->created_by;
    }

    public function delete(User $user, Task $task)
    {
        return ($user->role->id == 1 || $user->role->id == 2) && $user->id === $task->created_by;
    }
}
