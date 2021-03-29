<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RoleResource;
use App\Repositories\FarmRepository;

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
        $is_this_timeline = $request->route()->action['as'] == 'api.timeline';
        $farm = (new FarmRepository(app()))->find($request->farm ?? $request->farm_id);
        $return = [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'mobile'            => $this->mobile,
            'activity_points'   => $this->activity_points,
            'job_name'          => $this->job->name,
            'photo_url'         => isset($this->asset->asset_url)?$this->asset->asset_url:'',
            'status'            => $this->status,
            'mobile_verified'   => $this->mobile_verified,
            'email_verified'    => $this->email_verified,
            'roles'             => $this->getRoles(),
            'rating'            => $this->averageRating,
            // if timeline
            'is_following'      => $this->when($is_this_timeline, $this->isFollowedBy(auth()->user())), // Am I following him?
            'is_rated'          => $this->when($is_this_timeline, 'timeline'), // Did I rate him?
            
            // 'farm_roles'        => $this->when($this->farm, $this->getRoles($this->farm)),
            'farm_roles'        => $this->when($farm, $this->getRoles($farm)),
            // 'roles'             => RoleResource::collection($this->roles),
            // 'created_at'        => $this->created_at,
            // 'updated_at'        => $this->updated_at,
            // 'deleted_at'        => $this->deleted_at,            
        ];

        return $return;
    }


    public static function collection($resource){
        return new UserCollection($resource);
    }
}
