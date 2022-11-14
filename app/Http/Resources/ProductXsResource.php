<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductXsResource extends JsonResource
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
