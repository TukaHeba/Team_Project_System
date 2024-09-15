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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'due_date' => $this->due_date->toDateString(),
            'status' => $this->status,
            'assigned_to' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'type' => $this->user->type,
            ] : null,
            'project' => [
                'id' => $this->project->id,
                'name' => $this->project->name,
            ],
            'hours' => $this->hours,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toDateTimeString() : null,
        ];
    }
}
