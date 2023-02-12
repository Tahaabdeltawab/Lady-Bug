<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BusinessAdminSmResource extends JsonResource
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
            'branches' => $this->branches()->pluck('name'),
            'business_field' => @$this->businessField->name,
            'description' => $this->description,
            'main_asset' => @$this->main_asset[0]->asset_url,
            'com_name' => $this->com_name,
            'ladybug_rating' => !is_null($this->ladybug_rating) ? ceil($this->ladybug_rating * 100 / 5).'%' : null,
            'status_name' => @app('\App\Http\Controllers\API\BusinessAPIController')->statuses($this->status)['name'],
            'privacy_name' => @app('\App\Http\Controllers\API\BusinessAPIController')->privacies($this->privacy)['name'],
        ];
    }
}
