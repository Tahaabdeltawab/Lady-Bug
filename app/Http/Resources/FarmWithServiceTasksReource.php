<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmWithServiceTasksReource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
   
         $farm_detail['farmed_type_name'] = $this->farmed_type->name;
         $farm_detail['today_tasks'] = ServiceTaskResource::collection($this->service_tasks);
     //     $farm_detail[$this->farmed_type->name] = $this->service_tasks;
       
         return $farm_detail;
    }
}
