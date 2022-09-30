<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfflineConsultancyPlanResource extends JsonResource
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
            'consultancy_profile_id' => $this->consultancy_profile_id,
            'address' => $this->address,
            'date' => $this->date,
            'visit_price' => $this->visit_price,
            'year_price' => $this->year_price,
            'two_year_price' => $this->two_year_price,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at
        ];
    }
}
