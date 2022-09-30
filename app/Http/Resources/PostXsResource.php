<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\FarmRepository;
use App\Repositories\PostTypeRepository;

class PostXsResource extends JsonResource
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
        // $farm = (new FarmRepository(app()))->find($post->farm_id);
        $post_type = (new PostTypeRepository(app()))->find($post->post_type_id);

        $return = [
            'id' => $this->id,
            'content' => $post->content,
            'author' => new UserXsResource($this->author),
            'solved' => $post->solved,
            'image_assets' => collect($post->assets)->whereIn('asset_mime', config('myconfig.image_mimes'))->pluck('asset_url')->all(),
            'video_assets' => collect($post->assets)->whereIn('asset_mime', config('myconfig.video_mimes'))->pluck('asset_url')->all(),
            'post_type' => @new PostTypeResource($post_type), // @ because the 'farm' post type does not return by the global scope in the PostType model, so this will be null if the post type is 4 (farm)
            'comments_count' => $post->comments->count(),
            'liked_by_me' => $post->likers->where('id', auth()->id())->count() ? true : false ,
            'disliked_by_me' => $post->dislikers->where('id', auth()->id())->count() ? true : false ,
            'comments' => $request->routeIs('api.posts.show') ? CommentResource::collection($post->comments->whereNull('parent_id')) : [],
            'created_at' => $post->created_at->diffForHumans(),
            'shared' => $notShared ? null : new UserResource($this->shared->author),
            'share_id' => $post->id, // used to share the post. if this is an original post its value will be $this->id, if it's a shared post , its value will be the original post id
            'shared_content' => $notShared ? null : $this->content, // content added from the sharer
        ];

        return $return;
    }
}
