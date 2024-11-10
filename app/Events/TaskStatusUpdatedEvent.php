<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskStatusUpdatedEvent
{
   
        use Dispatchable, SerializesModels;
    
        public $task;
    
        /**
         * Create a new event instance.
         *
         * @return void
         */
        public function __construct(Task $task)
        {
            $this->task = $task;
        }
    }