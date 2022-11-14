<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'job_name'          => $this->job->name ?? "",
            'photo_url'         => $this->avatar ?: (isset($this->asset->asset_url) ? $this->asset->asset_url:''),
            'email'             => $this->email,
            'mobile'            => $this->mobile,
            'marital_status'    => $this->marital_status,
            'bio'               => $this->bio,
            'balance'           => $this->balance,
            'dob'               => $this->dob ? date('Y-m-d', strtotime($this->dob)) : null,
            'activity_points'   => $this->activity_points,
            'rating'            => $this->formattedAverageRating,
            'is_consultant'     => $this->is_consultant,

            'educations'        => EducationResource::collection($this->educations),
            'careers'           => CareerResource::collection($this->careers),
            'residences'        => ResidenceResource::collection($this->residences),
            'visiteds'          => VisitedResource::collection($this->visiteds),

        ];
    }
}
