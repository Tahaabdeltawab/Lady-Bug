<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsultancyProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'experience' => $this->experience,
            'ar' => $this->ar,
            'en' => $this->en,
            'consultancy_price' => $this->consultancy_price,
            'month_consultancy_price' => $this->month_consultancy_price,
            'year_consultancy_price' => $this->year_consultancy_price,
            'free_consultancy_price' => $this->free_consultancy_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
