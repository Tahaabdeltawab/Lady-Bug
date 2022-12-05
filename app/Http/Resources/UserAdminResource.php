<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Business;

class UserAdminResource extends JsonResource
{

    public function toArray($request)
    {
        $business = Business::find($request->business ?? $request->business_id);
        $return = [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'mobile'            => $this->mobile,
            'activity_points'   => $this->activity_points,
            'job_name'          => $this->job->name ?? "",
            'photo_url'         => $this->photo_url,
            'status'            => $this->status,
            'rating'            => $this->formattedAverageRating,
            'posts_count'       => $this->posts_count,
            'created_at'        => $this->created_at,
        ];

        return $return;
    }
}
