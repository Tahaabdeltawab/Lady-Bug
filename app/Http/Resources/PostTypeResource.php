<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostTypeResource extends JsonResource
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
            'name' => $request->header('Accept-Language') == 'all' ? $this->getTranslations('name') : $this->name
        ];

        // if($request->header('Accept-Language') == 'all')
        // {
        //     foreach(config('translatable.locales') as $locale)
        //     {
        //         $return["name_" . $locale . "_localized"] = $this->translate('name',$locale);
        //     }
        // }
        // else
        // {
        //     $return['name'] = $this->name;
        // }

        return $return;

    }
}
