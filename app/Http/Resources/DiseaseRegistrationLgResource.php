<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseRegistrationLgResource extends JsonResource
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
            'disease' => DiseaseXsResource::make($this->disease),
            'expected_name' => $this->expected_name,
            'status' => $this->status,
            'discovery_date' => date('Y-m-d', strtotime($this->discovery_date)),
            'user' => UserXsResource::make($this->user),
            'farm' => FarmXsResource::make($this->farm),
            'farm_report' => FarmReportXxsResource::make($this->farmReport),
            'infection_rate' => InfectionRateResource::make($this->infectionRate),
            'country' => CountryResource::make($this->country),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
