<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserXsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'job_name'          => $this->job->name ?? "",
            'photo_url'         => $this->avatar ? $this->avatar : (isset($this->asset->asset_url) ? $this->asset->asset_url:''),
        ];
    }
}