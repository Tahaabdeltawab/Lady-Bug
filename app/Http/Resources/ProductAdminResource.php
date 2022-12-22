<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $return = [
            'id' => $this->id,
            'price' => $this->price,
            'rating' => $this->formattedAverageRating,
            'assets' => $this->assets()->pluck('asset_url')->all(),
            'name' => $this->name,
            'description' => $this->description,
        ];

        return $return;
    }
}
