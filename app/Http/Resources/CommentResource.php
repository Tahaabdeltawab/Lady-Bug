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
            'content' => $this->content,
            'commenter_id' => $this->commenter_id,
            'parent_id' => $this->when($this->parent_id, $this->parent_id),
            'post_id' => $this->post_id,
            'assets' => collect($this->assets)->pluck('asset_url')->all(),
            'replies_count' => $this->when(!$this->parent_id, $this->replies->count()),
            'likers_count' => $this->likers->count(),
            'dislikers_count' => $this->dislikers->count(),
            'likers' => $this->when( auth()->id() == $this->commenter_id ,UserResource::collection($this->likers)),
            'dislikers' => $this->when( auth()->id() == $this->commenter_id ,UserResource::collection($this->dislikers)),
            'replies' => $this->when(!$this->parent_id, CommentResource::collection($this->replies)),
            'created_at' => $this->created_at->diffForHumans(),
            // 'updated_at' => $this->updated_at,
        ];
    }
}
