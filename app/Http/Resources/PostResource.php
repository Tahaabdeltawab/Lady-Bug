<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'title' => $this->title,
            'content' => $this->content,
            'author_id' => $this->author_id,
            'farm_id' => $this->farm_id,
            'farmed_type_id' => $this->farmed_type_id,
            'post_type_id' => $this->post_type_id,
            'solved' => $this->solved
        ];
    }
}
