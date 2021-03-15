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
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $farm = (new FarmRepository(app()))->find($request->farm_id);
        $return = [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'mobile'            => $this->mobile,
            'activity_points'   => $this->activity_points,
            'job_name'          => $this->job->name,
            'photo_url'         => isset($this->photo->asset_url)?$this->photo->asset_url:'',
            'status'            => $this->status,
            'mobile_verified'   => $this->mobile_verified,
            'email_verified'    => $this->email_verified,
            'farm_roles'        => $this->when($farm, $this->getRoles($farm)),
            'roles'             => $this->getRoles(),
            // 'roles'             => RoleResource::collection($this->roles),
            // 'created_at'        => $this->created_at,
            // 'updated_at'        => $this->updated_at,
            // 'deleted_at'        => $this->deleted_at,            
        ];

        return $return;
    }
}
