<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatusUpdate extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'task_id',
        'status',
        'type',
    ];
    public function task(){
        return $this->belongsTo(Task::class);
    }

    
}
