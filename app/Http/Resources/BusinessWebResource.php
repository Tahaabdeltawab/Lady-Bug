<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BusinessWebResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $prts = $this->users;
        foreach ($prts as $prt ) {
            $imgs[] = $prt->photo_url;
        }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'business_field_id' => $this->business_field_id,
            'description' => $this->description,
            'main_asset' => @$this->main_asset[0]->asset_url,
            'cover_asset' => @$this->cover_asset[0]->asset_url,
            'com_name' => $this->com_name,
            'status' => $this->status,
            'status_name' => @app('\App\Http\Controllers\API\BusinessAPIController')->statuses($this->status)['name'],
            'privacy' => $this->privacy,
            'privacy_name' => @app('\App\Http\Controllers\API\BusinessAPIController')->privacies($this->privacy)['name'],
            'lat' => @$this->location->latitude,
            'lon' => @$this->location->longitude,
            'is_following' => $this->isFollowedBy(auth()->user()), // Am I following him?
            'is_rated'          => $this->isRatedBy(auth()->id()), // Did I rate him?
            'followers_count'   => $this->followers->count(),
            'users_rating'      => $this->ratingPercent().'%',

            'participants_count' => count($prts),
            'participants_images' => $imgs,
            'user_permissions' => $this->userPermissions(),

        ];
    }
}
