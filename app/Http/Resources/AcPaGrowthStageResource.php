<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AcPaGrowthStageResource extends JsonResource
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
            'effect' => $this->effect,
            'pathogen_growth_stage_id' => $this->pathogen_growth_stage_id,
            'ac' => AcXsResource::make($this->ac),
            'assets' => $this->assets()->pluck('asset_url'),
        ];
    }
}
