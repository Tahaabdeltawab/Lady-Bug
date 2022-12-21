<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FarmWithReportsResource extends JsonResource
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
            'farm' => new FarmResource($this),
            'reports' => FarmReportXsResource::collection($this->farm_reports),
            'user_permissions' => $ps,
            'privacy_permissions' => $this->business->privacyPermissions(),
        ];
    }
}
