<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmReportXsResource extends JsonResource
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
            'created_at' => date('Y-m-d', strtotime($this->created_at)),
            'user' => new UserXsResource($this->user()->select('id', 'name', 'human_job_id')->first()),
        ];
    }
}
