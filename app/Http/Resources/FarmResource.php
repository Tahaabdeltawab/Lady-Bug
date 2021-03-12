<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmResource extends JsonResource
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
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'real' => $this->real,
            'archived' => $this->archived,
            'location' => $this->location,
            'farming_date' => $this->farming_date,
            'farming_compatibility' => $this->farming_compatibility,
            'home_plant_pot_size' => $this->home_plant_pot_size,
            'area' => $this->area,
            'area_unit_id' => $this->area_unit_id,
            'farm_activity_type_id' => $this->farm_activity_type_id,
            'farmed_type_id' => $this->farmed_type_id,
            'farmed_type_class_id' => $this->farmed_type_class_id,
            'farmed_number' => $this->farmed_number,
            'breeding_purpose_id' => $this->breeding_purpose_id,
            'home_plant_illuminating_source_id' => $this->home_plant_illuminating_source_id,
            'farming_method_id' => $this->farming_method_id,
            'farming_way_id' => $this->farming_way_id,
            'irrigation_way_id' => $this->irrigation_way_id,
            'soil_type_id' => $this->soil_type_id,
            'soil_detail_id' => $this->soil_detail_id,
            'irrigation_water_detail_id' => $this->irrigation_water_detail_id,
            'animal_drink_water_salt_detail_id' => $this->animal_drink_water_salt_detail_id
        ];
    }
}
