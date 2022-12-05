<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserConsAdminXsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'job_name'          => $this->job->name ?? "",
            'photo_url'         => $this->photo_url,
            'cons'              => new ConsultancyProfileAdminXsResource($this->consultancyProfile),
        ];
    }
}
