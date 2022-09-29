<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FertilizerXsResource extends JsonResource
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
            'producer' => $this->producer,
            'usage_rate' => $this->usage_rate,
        ];
    }
}
