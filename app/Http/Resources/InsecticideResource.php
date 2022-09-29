<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InsecticideResource extends JsonResource
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
            'acs' => $this->acs,
            'name' => $this->name,
            'assets' => $this->assets()->pluck('asset_url'),
            'dosage_form' => $this->dosage_form,
            'producer' => $this->producer,
            'country_id' => $this->country_id,
            'conc' => $this->conc,
            'reg_date' => $this->reg_date,
            'reg_num' => $this->reg_num,
            'mix_ph' => $this->mix_ph,
            'mix_rate' => $this->mix_rate,
            'expiry' => $this->expiry,
            'precautions' => $this->precautions,
            'notes' => $this->notes
        ];
    }
}
