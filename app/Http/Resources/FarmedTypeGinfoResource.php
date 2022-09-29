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
        $return = [
            'id' => $this->id,
            'farmed_type_id' => $this->farmed_type_id,
            'farmed_type' => $this->farmed_type->name,
            'farmed_type_stage_id' => $this->farmed_type_stage_id,
            'farmed_type_stage' => $this->farmed_type_stage->name,
            'assets' => $this->assets()->pluck('asset_url')
        ];

        if($request->header('Accept-Language') == 'all')
        {
            foreach(config('translatable.locales') as $locale)
            {
                $return["title_" . $locale . "_localized"] = $this->translate('title',$locale);
                $return["content_" . $locale . "_localized"] = $this->translate('content',$locale);
            }
        }
        else
        {
            $return['title'] = $this->title;
            $return['content'] = $this->content;
        }

        return $return;
    }
}
