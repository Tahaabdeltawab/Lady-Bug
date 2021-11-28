<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'report_type_id' => $this->report_type_id,
            'reportable_type' => $this->reportable_type,
            'reportable_id' => $this->reportable_id,
            'post' => $this->reportable instanceof \App\Models\Post ? new SimplePostResource($this->reportable) : $this->reportable,
            // 'post' => $this->when($request->report, $this->reportable instanceof \App\Models\Post ? new PostResource($this->reportable) : $this->reportable),
            'reporter_id' => $this->reporter_id,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
