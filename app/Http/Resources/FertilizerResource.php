<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FertilizerResource extends JsonResource
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
            'assets' => $this->assets()->pluck('asset_url'),
            'nut_elem_value' => collect($this->nutElemValue)->except('id')->mapWithKeys(function($elem,$key){
                return [$key => ['name' => __($key), 'value' => $elem]];
            }),
            'dosage_form' => $this->dosage_form,
            'producer' => $this->producer,
            'country' => $this->country,
            'addition_way' => $this->addition_way,
            'conc' => $this->conc,
            'reg_date' => $this->reg_date,
            'reg_num' => $this->reg_num,
            'mix_ph' => $this->mix_ph,
            'usage_rate' => $this->usage_rate,
            'expiry' => $this->expiry,
            'precautions' => $this->precautions,
            'notes' => $this->notes
        ];
    }
}
