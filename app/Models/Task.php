<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'priority',
        'due_date',
        'created_by',
        'assigned_to',
        'depends_on'
    ];

    protected $dates = ['deleted_at'];

    public function getDueDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y H:i') : null;
    }



    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = $value ? Carbon::parse($value) : null;
    }

    /**
     * Get the user who created the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }



    public function scopePriority($query, $priority)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }

        return $query;
    }

    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }

        return $query;
    }
    public function scopeType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }

        return $query;
    }
    public function scopeDue_date($query, $due_date)
    {
        if ($due_date) {
            return $query->where('due_date', $due_date);
        }

        return $query;
    }

    public function scopeAssignedToUser($query, $userName)
    {
        if ($userName) {
            return $query->where('assigned_to',$userName);
        }
        return $query;
    }

    // Scope for filtering by depends on task name or null
    public function scopeDependsOnTask($query, $taskName)
    {
        if ($taskName === 'null') {
            return $query->whereNull('depends_on');
        } elseif ($taskName) {
            return $query->whereHas('dependentTask', function ($q) use ($taskName) {
                $q->where('title', 'like', "%$taskName%");
            });
        }
        return $query;
    }


    public function scopeBlock($query)
    {
        return $query->where('status', 'Blocked');
    }

    /**
     * Get the user to whom the task is assigned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function dependentTask()
    {
        return $this->belongsTo(Task::class, 'depends_on');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function statusUpdate()
    {
        return $this->hasMany(TaskStatusUpdate::class);
    }
}
