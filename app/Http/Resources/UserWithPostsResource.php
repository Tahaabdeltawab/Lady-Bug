<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserWithPostsResource extends JsonResource
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
            'user' => new UserLgResource($this),
            'posts' => collect(PostXsResource::collection($this->posts()->accepted()->post()->get()))->where('canBeSeen', true)->values(),
        ];

    }
}
