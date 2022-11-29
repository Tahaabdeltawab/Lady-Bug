<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmedTypeClassResource extends JsonResource
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
            'farmed_type_id' => $this->farmed_type_id,
            'farmed_type_name' => $this->farmed_type->name,
            'name' => $request->header('Accept-Language') == 'all' ? $this->getTranslations('name') : $this->name
        ];

        return $return;
    }
}
