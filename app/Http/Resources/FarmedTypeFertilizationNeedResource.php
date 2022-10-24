<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmedTypeFertilizationNeedResource extends JsonResource
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
            'farmed_type_stage' => $this->farmedTypeStage,
            'per' => $this->per,
            // 'per' => @app('\App\Http\Controllers\API\FarmedTypeFertilizationNeedAPIController')->pers($this->per),
            'nut_elem_value' => $this->nutElemValue
        ];
    }
}
