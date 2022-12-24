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
        return [
            'farm' => FarmSmResource::make($this),
            'reports' => FarmReportXsWithTasksResource::collection($this->farm_reports),
            'user_permissions' => $this->business->userPermissions(),
            'privacy_permissions' => $this->business->privacyPermissions(),
        ];
    }
}
