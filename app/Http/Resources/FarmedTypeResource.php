<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmedTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $selected = auth()->check() ? (in_array($this->id, auth()->user()->favorites->pluck('id')->all()) ? 1 : 0) : 0;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'farm_activity_type_name' => $this->farm_activity_type->name,
            'photo_url' => $this->asset->asset_url,
            'selected' => $selected,
            'farmed_type_classes' => FarmedTypeClassResource::collection($this->farmed_type_classes),
            
            'farming_temperature' => $this->when($this->farming_temperature, $this->farming_temperature),
            'flowering_temperature' =>  $this->when($this->flowering_temperature, $this->flowering_temperature),
            'maturity_temperature' =>  $this->when($this->maturity_temperature, $this->maturity_temperature),
            'humidity' =>  $this->when($this->humidity, $this->humidity),
            'flowering_time' =>  $this->when($this->flowering_time, $this->flowering_time),
            'maturity_time' =>  $this->when($this->maturity_time, $this->maturity_time),
        ];
    }
}
