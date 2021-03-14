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
            'acidity_type' => new AcidityTypeResource($this->acidity_type),
            'acidity_value' => $this->acidity_value,
            'acidity_unit' =>  new MeasuringUnitResource($this->acidity_unit),
            'salt_type' => new SaltTypeResource($this->salt_type),
            'salt_concentration_value' => $this->salt_concentration_value,
            'salt_concentration_unit' =>  new MeasuringUnitResource($this->salt_concentration_unit),
            'salt_detail' => new SaltDetailResource($this->salt_detail)
        ];
    }
}
