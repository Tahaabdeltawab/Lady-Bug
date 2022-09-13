<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmedTypeNutValResource extends JsonResource
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
            'calories' => $this->calories,
            'total_fat' => $this->total_fat,
            'sat_fat' => $this->sat_fat,
            'cholesterol' => $this->cholesterol,
            'na' => $this->na,
            'k' => $this->k,
            'total_carb' => $this->total_carb,
            'dietary_fiber' => $this->dietary_fiber,
            'sugar' => $this->sugar,
            'protein' => $this->protein,
            'v_c' => $this->v_c,
            'fe' => $this->fe,
            'v_b6' => $this->v_b6,
            'mg' => $this->mg,
            'ca' => $this->ca,
            'v_d' => $this->v_d,
            'cobalamin' => $this->cobalamin
        ];
    }
}
