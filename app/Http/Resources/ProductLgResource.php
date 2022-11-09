<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductLgResource extends JsonResource
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
            'farmed_types' => FarmedTypeXsResource::collection($this->farmedTypes),
            'ads' => ProductAdResource::collection($this->ads),
            'shipping_cities' => ShippingCityResource::collection($this->shippingCities),
            'price' => $this->price,
            'seller_id' => $this->seller_id,
            'city' => $this->city->name,
            'district' => $this->district->name,
            'seller_mobile' => $this->seller_mobile,
            'sold' => $this->sold,
            'rating' => $this->averageRating,
            'other_links' => $this->other_links,
            'assets' => $this->assets()->pluck('asset_url')->all(),
            'insecticide' => InsecticideResource::make($this->insecticide),
            'fertilizer' => FertilizerResource::make($this->fertilizer),
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
