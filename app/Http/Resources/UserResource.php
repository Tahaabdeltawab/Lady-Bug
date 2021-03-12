<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'mobile'            => $this->mobile,
            'activity_points'   => $this->activity_points,
            'job_name'          => $this->job->name,
            // 'photo_url'         => $this->photo->asset_url,
            'photo_url'         => isset($this->photo->asset_url)?$this->photo->asset_url:'',
            'status'            => $this->status,
            'mobile_verified'   => $this->mobile_verified,
            'email_verified'    => $this->email_verified,
            // 'created_at'        => $this->created_at,
            // 'updated_at'        => $this->updated_at,
            // 'deleted_at'        => $this->deleted_at,            
        ];
    }
}
