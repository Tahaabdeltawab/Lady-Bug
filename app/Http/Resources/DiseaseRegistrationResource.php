<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseRegistrationResource extends JsonResource
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
            'assets' => $this->assets()->pluck('asset_url'),
            'disease_id' => $this->disease_id,
            'expected_name' => $this->expected_name,
            'status' => $this->status,
            'discovery_date' => $this->discovery_date,
            'user_id' => $this->user_id,
            'farm_id' => $this->farm_id,
            'farm_report_id' => $this->farm_report_id,
            'infection_rate_id' => $this->infection_rate_id,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'country_id' => $this->country_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
