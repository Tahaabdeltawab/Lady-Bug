<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'content' => $this->content,
            'commenter_id' => $this->commenter_id,
            'parent_id' => $this->when($this->parent_id, $this->parent_id),
            'post_id' => $this->post_id,
            'replies_count' => $this->when(!$this->parent_id, $this->replies->count()),
            'likers_count' => $this->likers->count(),
            'dislikers_count' => $this->dislikers->count(),
            'likers' => $this->when( auth()->id() == $this->commenter_id ,UserResource::collection($this->likers)),
            'dislikers' => $this->when( auth()->id() == $this->commenter_id ,UserResource::collection($this->dislikers)),
            'replies' => $this->when(!$this->parent_id, CommentResource::collection($this->replies)),
        ];
    }
}
