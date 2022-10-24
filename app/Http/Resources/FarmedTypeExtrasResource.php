<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmedTypeExtrasResource extends JsonResource
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
            'farmed_type_id' => $this->farmed_type_id,
            'irrigation_rate' => $this->irrigationRate,
            'seedling_type' => @app('\App\Http\Controllers\API\FarmedTypeExtrasAPIController')->seedling_types($this->seedling_type),
            'scientific_name' => $this->scientific_name,
            'history' => $this->history,
            'producer' => $this->producer,
            'description' => $this->description,
            'cold_hours' => $this->cold_hours,
            'illumination_hours' => $this->illumination_hours,
            'seeds_rate' => $this->seeds_rate,
            'production_rate' => $this->production_rate
        ];
    }
}
