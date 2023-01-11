<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessForUpdateResource extends JsonResource
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
        foreach ($prts as $prt ) {
            $imgs[] = $prt->photo_url;
        }
        return [
            'id' => $this->id,
            'agents' => $this->agents()->pluck('businesses.id'),
            'distributors' => $this->distributors()->pluck('businesses.id'),
            'branches' => $this->branches,
            'user_id' => $this->user_id,
            'business_field_id' => $this->business_field_id,
            'description' => $this->description,
            'main_asset' => @$this->main_asset[0]->asset_url,
            'cover_asset' => @$this->cover_asset[0]->asset_url,
            'com_name' => $this->getAttributes()['com_name'], // to get it without mutator, this is the only diff
            'status' => $this->status,
            'status_name' => @app('\App\Http\Controllers\API\BusinessAPIController')->statuses($this->status)['name'],
            'privacy' => $this->privacy,
            'privacy_name' => @app('\App\Http\Controllers\API\BusinessAPIController')->privacies($this->privacy)['name'],
            'mobile' => $this->mobile,
            'whatsapp' => $this->whatsapp,
            'lat' => @$this->location->latitude,
            'lon' => @$this->location->longitude,
            'address' => @$this->location->details,
            // 'country_id' => $this->country_id,
            'is_following' => $this->isFollowedBy(auth()->user()), // Am I following him?
            'participants_count' => count($prts),
            'participants_images' => $imgs,
            'user_role' => @__(collect(auth()->user()->get_roles($this->id))->first()['name']),
            'user_permissions' => $this->userPermissions(),
            'privacy_permissions' => $this->privacyPermissions(),
            'canBeSeen' => $this->canBeSeen(),

        ];
    }
}
