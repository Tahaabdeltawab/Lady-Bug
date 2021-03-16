<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\FarmRepository;
use App\Repositories\PostTypeRepository;

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
        $farm = (new FarmRepository(app()))->find($this->farm_id);
        $post_type = (new PostTypeRepository(app()))->find($this->post_type_id);
        return [
            'id' => $this->id,
            'title' => $this->when($this->title, $this->title),
            'content' => $this->content,
            'author_id' => $this->author_id,
            'farm' => $farm->farmed_type->name,
            'farmed_type_photo' => $farm->farmed_type->photo->asset_url,
            'solved' => $this->when($this->solved, $this->solved),
            'assets' => collect($this->assets)->pluck('asset_url')->all(),
            'post_type' => $post_type->name,
            'likers_count' => $this->likers->count(),
            'dislikers_count' => $this->dislikers->count(),
            'comments_count' => $this->comments->count(),
            'likers' => $this->when( auth()->id() == $this->author_id ,UserResource::collection($this->likers)),
            'dislikers' => $this->when( auth()->id() == $this->author_id ,UserResource::collection($this->dislikers)),
            'comments' => CommentResource::collection($this->comments->whereNull('parent_id')),
            // 'farmed_type_id' => $this->farmed_type_id,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
