<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmedTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $selected = auth()->check() ? (in_array($this->id, auth()->user()->favorites->pluck('id')->all()) ? 1 : 0) : 0;
        $return = [
            'id' => $this->id,
            'farm_activity_type_name' => $this->farm_activity_type->name,
            'farm_activity_type_id' => $this->farm_activity_type_id,
            'photo_url' => $this->asset->asset_url ?? 'https://via.placeholder.com/150',
            'selected' => $selected,
            'farmed_type_classes' => FarmedTypeClassResource::collection($this->farmed_type_classes),
        ];

        if($request->header('Accept-Language') == 'all')
        {
            foreach(config('translatable.locales') as $locale)
            {
                $return["name_" . $locale . "_localized"] = $this->translate($locale)->name;
            }
        }
        else
        {
            $return['name'] = $this->name;
        }

        if(auth()->user()->type == 'app_admin')
        $return = array_merge($return, [
            'flowering_time' =>  $this->flowering_time,
            'maturity_time' =>  $this->maturity_time,
            'farming_temperature' => $this->farming_temperature,
            'flowering_temperature' =>  $this->flowering_temperature,
            'maturity_temperature' =>  $this->maturity_temperature,
            'humidity' =>  $this->humidity,
            'suitable_soil_salts_concentration'=> $this->suitable_soil_salts_concentration,
            'suitable_water_salts_concentration'=> $this->suitable_water_salts_concentration,
            'suitable_ph'=> $this->suitable_ph,
            'suitable_soil_types'=> $this->suitable_soil_types,
        ]);

        return $return;
    }
}
