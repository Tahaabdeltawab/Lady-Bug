<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FertilizerSmResource extends JsonResource
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
            'producer' => $this->producer,
            'usage_rate' => $this->usage_rate,
            'nut_elem_value' => collect($this->nutElemValue)->except('id')->mapWithKeys(function($elem,$key){
                return [$key => ['name' => __($key), 'value' => $elem]];
            })->values(),
        ];
    }
}
