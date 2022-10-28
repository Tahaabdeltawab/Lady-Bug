<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseLgResource extends JsonResource
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
            'description' => $this->description,
            'causative' => $this->causative,
            'countries' => CountryResource::collection($this->countries),
            'pathogens' => PathogenLgResource::collection($this->pathogens),
            'resistant_farmed_types' => FarmedTypeXsResource::collection($this->resistant_farmed_types()->get(['farmed_types.id', 'farmed_types.name'])),
            'sensitive_farmed_types' => FarmedTypeXsResource::collection($this->sensitive_farmed_types()->get(['farmed_types.id', 'farmed_types.name'])),
        ];
    }
}
