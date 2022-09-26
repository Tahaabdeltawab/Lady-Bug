<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessWithTasksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
   
         $business_detail['business_name'] = $this->com_name;
         $business_detail['today_tasks'] = TaskResource::collection($this->tasks);
       
         return $business_detail;
    }
}
