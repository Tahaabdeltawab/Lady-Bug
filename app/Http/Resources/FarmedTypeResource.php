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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'farm_activity_type_name' => $this->farm_activity_type->name,
            'photo_url' => $this->photo->asset_url,
            'selected' => $selected,
            'farmed_type_classes' => FarmedTypeClassResource::collection($this->farmed_type_classes),
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
