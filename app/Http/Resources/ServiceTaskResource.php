<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTaskResource extends JsonResource
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
            'name' => $this->name,
            'start_at' => $this->start_at,
            'notify_at' => $this->notify_at,
            'farm_id' => $this->farm_id,
            'service_table_id' => $this->service_table_id,
            'task_type_id' => $this->task_type_id,
            'quantity' => $this->quantity,
            'quantity_unit_id' => $this->quantity_unit_id,
            'due_at' => $this->due_at,
            'done' => $this->done
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
