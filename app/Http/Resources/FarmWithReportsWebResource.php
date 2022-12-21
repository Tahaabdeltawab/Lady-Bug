<?php

namespace App\Http\Resources;

use App\Http\Helpers\Compatibility;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FarmWithReportsWebResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ps = DB::table('permissions')->join('permission_user', 'permissions.id', 'permission_user.permission_id')
                ->where('business_id', $this->business_id)
                ->where('user_id', auth()->id())
                ->pluck('permissions.name');
        return [
            'farm' => FarmSmResource::make($this),
            'reports' => FarmReportXsWithTasksResource::collection($this->farm_reports),
            'user_permissions' => $ps,
            'privacy_permissions' => $this->business->privacyPermissions(),
        ];
    }
}
