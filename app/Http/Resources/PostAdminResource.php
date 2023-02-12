<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $return = [
            'id' => $this->id,
            'status' => $this->status,
            'content' => $this->content,
            'author' => new UserXsResource($this->author),
            'solved' => $this->solved == true,
            'image_assets' => collect($this->assets)->whereIn('asset_mime', config('myconfig.image_mimes'))->pluck('asset_url')->all(),
            'video_assets' => collect($this->assets)->whereIn('asset_mime', config('myconfig.video_mimes'))->pluck('asset_url')->all(),
            'post_type' => @new PostTypeResource($this->post_type), // @ because the 'farm' post type does not return by the global scope in the PostType model, so this will be null if the post type is 4 (farm)
            'reactions_count' => $this->reactions_count,
            'comments_count' => $this->comments->count(),
            'likers_count' => $this->likers->count(),
            'dislikers_count' => $this->dislikers->count(),
            'created_date' => date('Y-m-d H:i', strtotime($this->created_at)),
        ];

        return $return;
    }
}
