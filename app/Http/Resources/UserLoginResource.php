<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Business;

class UserLoginResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'mobile'            => $this->mobile,
            'activity_points'   => $this->activity_points,
            'job_name'          => $this->job->name ?? "",
            'photo_url'         => $this->avatar ?: (isset($this->asset->asset_url) ? $this->asset->asset_url:''),
            'status'            => $this->status,
            'is_notifiable'     => $this->is_notifiable,
            'mobile_verified'   => $this->mobile_verified,
            'email_verified'    => $this->email_verified,
            'roles'             => $this->get_roles(),
            'type'              => $this->type,
            'rating'            => $this->averageRating,
            'income'            => $this->income,
            'balance'           => $this->balance,
            'dob'               => $this->dob ? date('Y-m-d', strtotime($this->dob)) : null,
            'city_id'           => (string) $this->city_id,
            'city'              => $this->city->name ?? '',
        ];

        return $return;
    }

}
