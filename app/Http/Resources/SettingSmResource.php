<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingSmResource extends JsonResource
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
            'name' => $this->name,
            'value' => $this->value ?? @$this->asset->asset_url,
        ];
    }
}
