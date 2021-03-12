<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaltDetailResource extends JsonResource
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
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,at,
            'saltable_type' => $this->saltable_type,
            'PH' => $this->PH,
            'CO3' => $this->CO3,
            'HCO3' => $this->HCO3,
            'Cl' => $this->Cl,
            'SO4' => $this->SO4,
            'Ca' => $this->Ca,
            'Mg' => $this->Mg,
            'K' => $this->K,
            'Na' => $this->Na,
            'Na2CO3' => $this->Na2CO3
        ];
    }
}
