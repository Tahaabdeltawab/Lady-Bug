<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'price' => $this->price,
            'description' => $this->description,
            'seller_id' => $this->seller_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'seller_mobile' => $this->seller_mobile,
            'sold' => $this->sold,
            'other_links' => $this->other_links,
            'internal_assets' => $this->internal_assets()->pluck('asset_url')->all(),
            'external_assets' => $this->external_assets()->pluck('asset_url')->all(),
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
