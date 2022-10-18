<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Business;

class UserResource extends JsonResource
{
    // public function __construct($farm, $user)
    // {
    //     parent::__construct(\App\Models\User::class);
    //     $this->farm = $farm;
    // }

    protected $farm;

    public function farm($farm){
        $this->farm = $farm;
        return $this;
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $request->route()->action['as'] is equal to $request->routeIs($route_name)
        // $is_this_timeline = in_array($request->route()->action['as'], ['api.timeline', 'api.posts.show', 'api.farms.posts.index']);
        $business = Business::find($request->business ?? $request->business_id);
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
            // 'roles'             => $this->getRoles(),
            'roles'             => $this->get_roles(),
            'type'              => $this->type,
            'rating'            => $this->averageRating,

            'income'            => $this->income,
            'balance'           => $this->balance,
            'dob'               => $this->dob ? date('Y-m-d', strtotime($this->dob)) : null,
            'city_id'           => (string) $this->city_id,
            'city'              => $this->city->name ?? '',

            // 'is_following'      => $this->isFollowedBy(auth()->user()), // Am I following him?
            'is_rated'          => $this->isRatedBy(auth()->id()), // Did I rate him?

            // 'business_roles'        => $this->when($this->business, $this->getRoles($this->business)),
            'business_roles'        => $this->when($business, $this->getRoles($business)),
            // 'roles'             => RoleResource::collection($this->roles),
            // 'created_at'        => $this->created_at,
            // 'updated_at'        => $this->updated_at,
            // 'deleted_at'        => $this->deleted_at,
            // 'avatar'            => $this->avatar,
            // 'provider'          => $this->provider,
            // 'fcm'               => $this->fcm,
        ];

        return $return;
    }


    public static function collection($resource){
        return new UserCollection($resource);
    }
}
