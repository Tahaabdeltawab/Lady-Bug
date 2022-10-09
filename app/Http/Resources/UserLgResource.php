<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserLgResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'bio'               => $this->bio,
            'job_name'          => $this->job->name ?? "",
            'photo_url'         => $this->avatar ?: (isset($this->asset->asset_url) ? $this->asset->asset_url:''),
            'users_rating'      => $this->ratingPercent().'%',
            'ladybug_rating'    => $this->ladybug_rating() .'%',

            'id_verified'       => $this->id_verified,
            'made_transaction'  => $this->made_transaction,
            'met_ladybug'       => $this->met_ladybug,
            'reactive'          => $this->reactive,

            'followers_count'   => $this->followers()->count(),
            'is_following'      => $this->isFollowedBy(auth()->user()), // Am I following him?
            'is_rated'          => $this->isRatedBy(auth()->id()), // Did I rate him?
        ];
    }
}
