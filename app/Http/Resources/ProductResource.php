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
        $return = [
            'id' => $this->id,
            'price' => $this->price,
            'seller_id' => $this->seller_id,
            'farmed_type' => $this->farmed_type->name,
            'city' => $this->city->name,
            'district' => $this->district->name,
            'seller_mobile' => $this->seller_mobile,
            'sold' => $this->sold,
            'rating' => (double) $this->averageRating,
            'other_links' => $this->other_links,
            'internal_assets' => $this->internal_assets()->pluck('asset_url')->all(),
            'external_assets' => $this->external_assets()->pluck('asset_url')->all(),
        ];

        if($request->header('Accept-Language') == 'all')
        {
            foreach(config('translatable.locales') as $locale)
            {
                $return["name_" . $locale . "_localized"] = $this->translate('name',$locale);
                $return["description_" . $locale . "_localized"] = $this->translate('description',$locale);
            }
        }
        else
        {
            $return['name'] = $this->name;
            $return['description'] = $this->description;
        }

        return $return;
    }
}
