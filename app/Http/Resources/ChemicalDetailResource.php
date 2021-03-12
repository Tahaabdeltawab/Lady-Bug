<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChemicalDetailResource extends JsonResource
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
            // 'updated_at' => $this->updated_at,
            'type' => $this->type,
            'acidity' => $this->acidity,
            'acidity_value' => $this->acidity_value,
            'acidity_unit_id' => $this->acidity_unit_id,
            'salt_type' => $this->salt_type,
            'salt_concentration_value' => $this->salt_concentration_value,
            'salt_concentration_unit_id' => $this->salt_concentration_unit_id,
            'salt_detail_id' => $this->salt_detail_id
        ];
    }
}
