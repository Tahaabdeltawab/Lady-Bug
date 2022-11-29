<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InformationResource extends JsonResource
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
            'title' => $request->header('Accept-Language') == 'all' ? $this->getTranslations('title') : $this->title,
            'content' => $request->header('Accept-Language') == 'all' ? $this->getTranslations('content') : $this->content,
        ];

        return $return;
    }
}
