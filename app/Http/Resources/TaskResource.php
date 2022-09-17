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
            'task_type_id' => $this->task_type_id,
            'insecticide_id' => $this->insecticide_id,
            'fertilizer_id' => $this->fertilizer_id,
            'quantity' => $this->quantity,
            'quantity_unit' => $this->quantity_unit,
            'done' => $this->done
        ];
    }
}
