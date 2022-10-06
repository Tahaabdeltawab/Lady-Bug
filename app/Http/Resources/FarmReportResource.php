<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmReportResource extends JsonResource
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
            'business_id' => $this->business_id,
            'farm_id' => $this->farm_id,
            'farmed_type_stage_id' => $this->farmed_type_stage_id,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'fertilization_start_date' => $this->fertilization_start_date,
            'fertilization_unit' => $this->fertilization_unit,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
