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
            // 'title' => $this->when($this->title, $this->title),
            'content' => $this->content,
            'author' => new UserResource($this->author),

            // 'farm' => $this->when($farm, @$farm->farmed_type->name),
            // 'farmed_type_photo' => $this->when($farm, @$farm->farmed_type->asset->asset_url),
            // 'solved' => $this->when($this->solved, $this->solved),
            'farm' => @$farm->farmed_type->name, //will show null if no farmed_type
            'farmed_type_photo' => @$farm->farmed_type->asset->asset_url,
            'farmed_type_id' => $this->farmed_type_id ?? @$farm->farmed_type_id ,
            'solved' => $this->solved,

            'image_assets' => collect($this->assets)->whereIn('asset_mime', config('myconfig.image_mimes'))->pluck('asset_url')->all(),
            'video_assets' => collect($this->assets)->whereIn('asset_mime', config('myconfig.video_mimes'))->pluck('asset_url')->all(),

            'post_type' => @new PostTypeResource($post_type), // @ because the 'farm' post type does not return by the global scope in the PostType model, so this will be null if the post type is 4 (farm)
            'likers_count' => $this->likers->count(),
            'dislikers_count' => $this->dislikers->count(),
            'comments_count' => $this->comments->count(),
            'likers' => $this->when( auth()->id() == $this->author_id ,UserResource::collection($this->likers)),
            'dislikers' => $this->when( auth()->id() == $this->author_id ,UserResource::collection($this->dislikers)),
            'liked_by_me' => $this->likers->where('id', auth()->id())->count() ? true : false ,
            'disliked_by_me' => $this->dislikers->where('id', auth()->id())->count() ? true : false ,
            'comments' => CommentResource::collection($this->comments->whereNull('parent_id')),
            // 'farmed_type_id' => $this->farmed_type_id,
            'created_at' => $this->created_at->diffForHumans(),
            // 'updated_at' => $this->updated_at,
        ];
    }
}
