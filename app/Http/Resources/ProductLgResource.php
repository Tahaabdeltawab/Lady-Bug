<?php

namespace App\Http\Resources;

use App\Models\User;
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
            'product_type' => $this->productType,
            'farmed_types' => FarmedTypeXsResource::collection($this->farmedTypes),
            'ads' => ProductAdResource::collection($this->ads),
            'shipping_cities' => ShippingCityResource::collection($this->shippingCities),
            'price' => $this->price,
            'seller' => UserXsResource::make($this->seller()->select(User::$selects)->first()),
            'business' => $this->business ? $this->business->only(['id','com_name', 'description']) : null,
            'city' => CityXsResource::make($this->city),
            'district' => DistrictXsResource::make($this->district),
            'seller_mobile' => $this->seller_mobile,
            'sold' => $this->sold,
            'rating' => $this->formattedAverageRating,
            'other_links' => $this->other_links,
            'assets' => $this->assets()->pluck('asset_url')->all(),
            'insecticide' => InsecticideResource::make($this->insecticide),
            'fertilizer' => FertilizerResource::make($this->fertilizer),
            'name' => $request->header('Accept-Language') == 'all' ? $this->getTranslations('name') : $this->name,
            'description' => $request->header('Accept-Language') == 'all' ? $this->getTranslations('description') : $this->description,
        ];

        return $return;
    }
}
