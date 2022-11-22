<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileWebResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'job_name'          => $this->job->name ?? "",
            'photo_url'         => $this->photo_url,

            'users_rating'      => $this->ratingPercent().'%',
            'ladybug_rating'    => $this->ladybug_rating() .'%',
            'followers_count'   => $this->followers->count(),
            'id_verified'       => $this->id_verified,
            'made_transaction'  => $this->made_transaction,
            'met_ladybug'       => $this->met_ladybug,
            'reactive'          => $this->reactive,

            'is_following'      => $this->isFollowedBy(auth()->user()), // Am I following him?
            'is_rated'          => $this->isRatedBy(auth()->id()), // Did I rate him?


            'consultant'        => ConsultancyProfileXsWebResource::make($this->consultancyProfile),

            'bio'               => $this->bio,
            'educations'        => EducationResource::collection($this->educations),
            'careers'           => CareerResource::collection($this->careers),
            'residences'        => ResidenceResource::collection($this->residences),
            'visiteds'          => VisitedResource::collection($this->visiteds),
            'marital_status'    => $this->marital_status,
            'dob'               => $this->dob ? date('Y-m-d', strtotime($this->dob)) : null,

        ];
    }
}
