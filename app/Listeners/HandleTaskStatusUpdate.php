<?php

namespace App\Listeners;

use App\Events\StatusUpdateLogEvent;
use App\Models\Task;
use App\Models\TaskStatusUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleTaskStatusUpdate
{
    /**
     * Handle the event.
     *
     * @param  TaskStatusUpdatedEvent  $event
     * @return void
     */
    public function handle(StatusUpdateLogEvent $event)
    {
        TaskStatusUpdate::create([
            'task_id' => $event->task->id,
            'status' => $event->task->status,
            'type' => $event->task->type,
        ]);

    }
}
