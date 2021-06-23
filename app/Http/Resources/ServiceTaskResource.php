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
            'farm_id' => $this->farm_id,
            'service_table_id' => $this->service_table_id,
            'task_type_id' => $this->task_type_id,
            'task_type_name' => $this->task_type->name,
            'quantity' => $this->quantity,
            'quantity_unit_id' => $this->quantity_unit_id,
            'start_at' => date('Y-m-d', strtotime($this->start_at)),
            'notify_at' => date('Y-m-d', strtotime($this->notify_at)),
            // 'due_at' => date('Y-m-d', strtotime($this->due_at)),
            'due_at' => $this->due_at,
            'done' => $this->done
        ];
    }
}
