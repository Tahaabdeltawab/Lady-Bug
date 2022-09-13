<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarketingDataResource extends JsonResource
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
            'year' => $this->year,
            'country_id' => $this->country_id,
            'production' => $this->production,
            'consumption' => $this->consumption,
            'export' => $this->export,
            'price_avg' => $this->price_avg
        ];
    }
}
