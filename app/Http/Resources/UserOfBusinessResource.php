<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserOfBusinessResource extends JsonResource
{
    public function toArray($request)
    {
        $ps = DB::table('permissions')->join('permission_user', 'permissions.id', 'permission_user.permission_id')
                ->where('business_id', $request->business)
                ->where('user_id', $this->id)
                ->pluck('permissions.name');
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'job_name'          => $this->job->name ?? "",
            'photo_url'         => $this->photo_url,
            'start_date'        => $this->start_date,
            'end_date'          => $this->end_date,
            'user_permissions'  => $ps,
        ];
    }
}
