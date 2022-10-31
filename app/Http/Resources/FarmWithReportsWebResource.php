<?php

namespace App\Http\Resources;

use App\Http\Helpers\Compatibility;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $data['farm'] = FarmSmResource::make($this);
        $data['reports'] = FarmReportXsWithTasksResource::collection($this->farm_reports);
        return $data;
    }
}
