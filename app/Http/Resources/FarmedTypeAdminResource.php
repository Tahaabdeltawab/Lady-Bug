<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmedTypeAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $return = [
            'id' => $this->id,
            'name' => $this->name,
            'farm_activity_type' => FarmActivityTypeResource::make($this->farm_activity_type),
            'photo_url' => @$this->asset->asset_url,
            'flowering_time' =>  $this->flowering_time,
            'maturity_time' =>  $this->maturity_time,
            'farming_temperature' => $this->farming_temperature,
            'flowering_temperature' =>  $this->flowering_temperature,
            'maturity_temperature' =>  $this->maturity_temperature,
            'humidity' =>  $this->humidity,
            'suitable_soil_salts_concentration'=> $this->suitable_soil_salts_concentration,
            'suitable_water_salts_concentration'=> $this->suitable_water_salts_concentration,
            'suitable_ph'=> $this->suitable_ph,
            'suitable_soil_types'=> $this->suitableSoilTypes(),
            'parent' => FarmedTypeAdminResource::make($this->parent),
        ];

        return $return;
    }
}
