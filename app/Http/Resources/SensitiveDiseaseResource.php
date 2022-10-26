<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SensitiveDiseaseResource extends JsonResource
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
            'disease' => $this->disease,
            'farmed_type_stage' => $this->farmedTypeStage,
            'assets' => $this->assets()->pluck('asset_url'),
        ];
    }
}
