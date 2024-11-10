<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'created_by'  => $this->created_by,
            'assigned_to' => $this->assigned_to,
            'title'       => $this->title,
            'description' => $this->description,
            'type'        => $this->type,
            'status'      => $this->status,
            'priority'    => $this->priority,
            'due_date'    => $this->due_date,
            'depends_on'  => $this->depends_on,
        ];
    }
}
