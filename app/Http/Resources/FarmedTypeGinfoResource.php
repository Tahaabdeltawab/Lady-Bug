<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmedTypeGinfoResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'farmed_type_id' => $this->farmed_type_id,
            'farmed_type' => $this->farmed_type->name,
            'farmed_type_stage' => $this->farmed_type_stage->name,
            'assets' => collect($this->assets)->pluck('asset_url')->all()
        ];
    }
}
