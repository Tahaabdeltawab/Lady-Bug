<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseResource extends JsonResource
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
            'countries' => CountryResource::collection($this->countries),
            'pathogens' => PathogenXsResource::collection($this->pathogens()->get(['pathogens.id', 'pathogens.name'])),
        ];
    }
}
