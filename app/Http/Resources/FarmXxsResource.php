<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmXxsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $farm_detail['id'] = $this->id;
        $farm_detail['code'] = $this->code;
        $farm_detail['farmed_type_name'] = @$this->farmed_type->name;
        $farm_detail['farmed_type_photo'] = @$this->farmed_type->asset->asset_url;

        return $farm_detail;
    }
}
