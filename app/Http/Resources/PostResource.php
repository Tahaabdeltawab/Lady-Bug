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
        $post = $this->shared == null ? $this : $this->shared;
        $farm = (new FarmRepository(app()))->find($post->farm_id);
        $post_type = (new PostTypeRepository(app()))->find($post->post_type_id);

        $return = [
            'id' => $this->id,
            'status' => $this->status,
            'content' => $post->content,
            'author' => new UserResource($this->author),
            'farmed_type_photo' => @$farm->farmed_type->asset->asset_url,
            'farmed_type_id' => $post->farmed_type_id ?? @$farm->farmed_type_id ,
            'solved' => $post->solved,
            'image_assets' => collect($post->assets)->whereIn('asset_mime', config('myconfig.image_mimes'))->pluck('asset_url')->all(),
            'video_assets' => collect($post->assets)->whereIn('asset_mime', config('myconfig.video_mimes'))->pluck('asset_url')->all(),
            'post_type' => @new PostTypeResource($post_type), // @ because the 'farm' post type does not return by the global scope in the PostType model, so this will be null if the post type is 4 (farm)
            'likers_count' => $post->likers->count(),
            'dislikers_count' => $post->dislikers->count(),
            'comments_count' => $post->comments->count(),
            'likers' => [],//$post->when( auth()->id() == $post->author_id ,UserResource::collection($post->likers)),
            'dislikers' => [],//$post->when( auth()->id() == $post->author_id ,UserResource::collection($post->dislikers)),
            'liked_by_me' => $post->likers->where('id', auth()->id())->count() ? true : false ,
            'disliked_by_me' => $post->dislikers->where('id', auth()->id())->count() ? true : false ,
            'comments' => $request->routeIs('api.posts.show') ? CommentResource::collection($post->comments->whereNull('parent_id')) : [],
            'created_at' => $post->created_at->diffForHumans(),
            'shared' => $this->shared == null ? null : new UserResource($this->shared->author),
            'share_id' => $post->id, // used to share the post. if this is an original post its value will be $this->id, if it's a shared post , its value will be the original post id
        ];

        return $return;
    }
}
