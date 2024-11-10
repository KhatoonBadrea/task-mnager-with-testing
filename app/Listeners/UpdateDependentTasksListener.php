<?php
namespace App\Listeners;

use App\Models\Task;
use App\Models\TaskStatusUpdate;
use App\Events\TaskStatusUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateDependentTasksListener
{
    /**
     * Handle the event.
     *
     * @param  TaskStatusUpdatedEvent  $event
     * @return void
     */
    public function handle(TaskStatusUpdatedEvent $event)
    {
        TaskStatusUpdate::create([
            'task_id' => $event->task->id,
            'status' => $event->task->status,
            'type' => $event->task->type,
        ]);
        $dependentTasks = Task::where('depends_on', $event->task->id)->get();

        foreach ($dependentTasks as $dependentTask) {
            if ($event->task->status == 'Completed') {
                $event->task->due_date = now();
                $dependentTask->status = 'Open';
                $dependentTask->depends_on = null;
                $dependentTask->save();
                $event->task->save();
            }
            TaskStatusUpdate::create([
                'task_id' => $dependentTask->id,
                'status' => 'Open',
                'type' => $dependentTask->type,
            ]);
            $dependentTask->save();
        }
    }
}
