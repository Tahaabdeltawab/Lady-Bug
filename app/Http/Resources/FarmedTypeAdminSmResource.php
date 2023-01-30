<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmedTypeAdminSmResource extends JsonResource
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
            'name' => $this->name,
            'farm_activity_type' => @$this->farm_activity_type->name,
            'photo_url' => @$this->asset->asset_url,
            'flowering_time' =>  $this->flowering_time,
            'maturity_time' =>  $this->maturity_time,
        ];

        return $return;
    }
}
