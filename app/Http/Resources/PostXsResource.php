<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        // $this always refers to the newer post either original or shared
        // $this->shared refers to the original post
        // $post always refers to an original post (not shared or original of the shared)
        // post is original (not shared) if it has no original post ($this->shared == null)
        $original = $this->shared == null;
        $post = $original ? $this : $this->shared;

        $return = [
            'id' => $this->id,
            'content' => $post->content,
            'author' => new UserSmResource($this->author),
            'solved' => $post->solved,
            'image_assets' => collect($post->assets)->whereIn('asset_mime', config('myconfig.image_mimes'))->pluck('asset_url')->all(),
            'video_assets' => collect($post->assets)->whereIn('asset_mime', config('myconfig.video_mimes'))->pluck('asset_url')->all(),
            'post_type' => @new PostTypeResource($post->post_type), // @ because the 'farm' post type does not return by the global scope in the PostType model, so this will be null if the post type is 4 (farm)
            'comments_count' => $post->comments->count(),
            'likers_count' => $post->likers->count(),
            'dislikers_count' => $post->dislikers->count(),
            'liked_by_me' => $post->likers->where('id', auth()->id())->count() ? true : false,
            'disliked_by_me' => $post->dislikers->where('id', auth()->id())->count() ? true : false,
            'comments' => $request->routeIs('api.posts.show') ? CommentResource::collection($post->comments->whereNull('parent_id')) : [],
            'created_at' => $post->created_at->diffForHumans(),
            'created_date' => $post->created_at,
            'share_created_at' => $original ? null : $this->created_at->diffForHumans(),
            'share_created_date' => $original ? null : $this->created_at,
            'shared' => $original ? null : new UserResource($this->shared->author), // sharer
            'shared_content' => $original ? null : $this->content, // content added from the sharer
            'share_id' => $post->id, // used to share the post. if this is an original post its value will be $this->id, if it's a shared post , its value will be the original post id
            'business' => BusinessMdResource::make($this->business),
            'canBeSeen' => $this->business ? $this->business->userCan('show-posts') : true,
        ];

        return $return;
    }
}
