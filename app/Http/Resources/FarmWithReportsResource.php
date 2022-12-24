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
        return [
            'farm' => new FarmResource($this),
            'reports' => FarmReportXsResource::collection($this->farm_reports),
            'user_permissions' => $this->business->userPermissions(),
            'privacy_permissions' => $this->business->privacyPermissions(),
        ];
    }
}
