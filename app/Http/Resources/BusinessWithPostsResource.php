<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessWithPostsResource extends JsonResource
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
            'main_asset' => @$this->main_asset[0]->asset_url,
            'cover_asset' => @$this->cover_asset[0]->asset_url,
            'ladybug_rating' => '50%',
            'users_rating' => '70%',
            'followers_count' => $this->followers()->count(),
            'participants_count' => $this->users()->count(),
            'posts' => PostXsResource::collection($this->posts()->accepted()->notVideo()->get()),
        ];
    }
}
