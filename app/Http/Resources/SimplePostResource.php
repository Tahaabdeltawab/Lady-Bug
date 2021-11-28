<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\FarmRepository;
use App\Repositories\PostTypeRepository;

class SimplePostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $notShared = $this->shared == null;
        $post = $notShared ? $this : $this->shared;

        $return = [
            'id' => $this->id,
            'status' => $this->status,
            'content' => $post->content,
            'author' => new SimpleUserResource($this->author),
        ];

        return $return;
    }
}
