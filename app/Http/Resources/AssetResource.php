<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
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
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'asset_name' => $this->asset_name,
            'asset_url'  => $this->asset_url,
            'asset_size' => $this->asset_size,
            'asset_mime' => $this->asset_mime,
            'assetable_type' => $this->assetable_type
        ];
    }
}
