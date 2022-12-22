<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BusinessWithPostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ps = DB::table('permissions')->join('permission_user', 'permissions.id', 'permission_user.permission_id')
                ->where('business_id', $this->id)
                ->where('user_id', auth()->id())
                ->pluck('permissions.name');
        return [
            'id' => $this->id,
            'com_name' => $this->com_name,
            'description' => $this->description,
            'main_asset' => @$this->main_asset[0]->asset_url,
            'cover_asset' => @$this->cover_asset[0]->asset_url,
            'ladybug_rating' => !is_null($this->ladybug_rating) ? ceil($this->ladybug_rating * 100 / 5).'%' : null,
            'users_rating' => $this->ratingPercent().'%',
            'followers_count' => $this->followers()->count(),
            'participants_count' => $this->users()->count(),
            'posts' => collect(PostXsResource::collection($this->posts()->accepted()->post()->get()))->where('canBeSeen', true)->values(),
            'user_role' => @__(collect(auth()->user()->get_roles($this->id))->first()['name']),
            'user_permissions' => $ps,
            'privacy_permissions' => $this->privacyPermissions(),
            'canBeSeen' => $this->canBeSeen(),
        ];
    }
}
