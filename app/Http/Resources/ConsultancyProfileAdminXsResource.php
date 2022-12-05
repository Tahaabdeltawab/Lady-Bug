<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsultancyProfileAdminXsResource extends JsonResource
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
            'id' => $this->id,
            'status' => $this->status,
            'work_fields' => WorkFieldResource::collection($this->workFields),
            'experience' => $this->experience,
            'online' => !empty($this->consultancy_price),
            'offline' => $this->offlineConsultancyPlans()->count() > 0,
        ];
    }
}
