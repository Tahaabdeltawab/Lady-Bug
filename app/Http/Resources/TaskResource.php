<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'farm_report_id' => $this->farm_report_id,
            'farm_id' => $this->farm_id,
            'business_id' => $this->business_id,
            'date' => $this->date,
            'week' => $this->week,
            'task_type' => new TaskTypeResource($this->task_type),
            'insecticide' => new InsecticideXsResource($this->insecticide()->select('id', 'name')->first()),
            'fertilizer' => new FertilizerXsResource($this->fertilizer()->select('id', 'name')->first()),
            'quantity' => $this->quantity,
            'quantity_unit' => $this->quantity_unit,
            'notes' => $this->notes,
            'done' => $this->done,
            'can_finish' => $this->business->userCan('finish-task'),
        ];
    }
}
