<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmingWayResource extends JsonResource
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
            'type' => $this->type,
            'name' => $request->header('Accept-Language') == 'all' ? $this->getTranslations('name') : $this->name
        ];

        return $return;
    }
}
