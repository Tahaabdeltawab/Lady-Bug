<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseCausativeResource extends JsonResource
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
            'disease_id' => $this->disease_id,
            'temp_gt' => $this->temp_gt,
            'temp_lt' => $this->temp_lt,
            'humidity_gt' => $this->humidity_gt,
            'humidity_lt' => $this->humidity_lt,
            'ph_gt' => $this->ph_gt,
            'ph_lt' => $this->ph_lt,
            'soil_salts_gt' => $this->soil_salts_gt,
            'soil_salts_lt' => $this->soil_salts_lt,
            'water_salts_gt' => $this->water_salts_gt,
            'water_salts_lt' => $this->water_salts_lt
        ];
    }
}
