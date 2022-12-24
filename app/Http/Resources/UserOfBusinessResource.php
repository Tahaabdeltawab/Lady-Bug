<?php

namespace App\Http\Resources;

use App\Models\Business;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserOfBusinessResource extends JsonResource
{
    public function toArray($request)
    {
        $business = Business::find($request->business);
        return [
            'id'                => $this->id,
            'business_id'       => $business->id,
            'name'              => $this->name,
            'job_name'          => $this->job->name ?? "",
            'photo_url'         => $this->photo_url,
            'start_date'        => $this->start_date,
            'end_date'          => $this->end_date,
            'user_permissions'  => $business ? $business->userPermissions($this->id) : [],
        ];
    }
}
