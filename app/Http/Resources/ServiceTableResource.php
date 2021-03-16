<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'name' => $this->name,
            'farm_id' => $this->farm_id,
            'tasks_count' => $this->tasks->count(),
            'tasks' => ServiceTaskResource::collection($this->tasks),
        ];
    }
}
