<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmReportXsWithTasksResource extends JsonResource
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
            'notes' => $this->notes,
            'user' => new UserXsResource($this->user()->select('id', 'name', 'human_job_id')->first()),
            'tasks' => TaskResource::collection($this->tasks()->take(3)->get()),
        ];
    }
}