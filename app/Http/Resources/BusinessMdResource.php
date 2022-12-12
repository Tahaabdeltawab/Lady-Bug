<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessMdResource extends JsonResource
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
            'com_name' => $this->com_name,
            'description' => $this->description,
            'location' => @$this->location->details,
            'main_asset' => @$this->main_asset[0]->asset_url,
        ];
    }
}
