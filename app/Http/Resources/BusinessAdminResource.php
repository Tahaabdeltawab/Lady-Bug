<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BusinessAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $prts = $this->users;
        $imgs = $prts->pluck('photo_url');
        return [
            'id' => $this->id,
            'agents' => $this->agents()->pluck('businesses.com_name'),
            'distributors' => $this->distributors()->pluck('businesses.com_name'),
            'branches' => $this->branches()->pluck('name'),
            'user' => new UserXsResource($this->user),
            'business_field' => $this->businessField->name,
            'description' => $this->description,
            'main_asset' => @$this->main_asset[0]->asset_url,
            'cover_asset' => @$this->cover_asset[0]->asset_url,
            'com_name' => $this->com_name,
            'status_name' => @app('\App\Http\Controllers\API\BusinessAPIController')->statuses($this->status)['name'],
            'privacy_name' => @app('\App\Http\Controllers\API\BusinessAPIController')->privacies($this->privacy)['name'],
            'mobile' => $this->mobile,
            'whatsapp' => $this->whatsapp,
            'ladybug_rating' => !is_null($this->ladybug_rating) ? ceil($this->ladybug_rating * 100 / 5).'%' : null,
            'location' => LocationResource::make($this->location),
            'participants_count' => count($prts),
            'participants_images' => $imgs,
        ];
    }
}
