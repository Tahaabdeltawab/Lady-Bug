<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BusinessResource extends JsonResource
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
                ->where('business_id', $this->id)
                ->where('user_id', auth()->id())
                ->pluck('permissions.name');
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
            'com_name' => $this->com_name,
            'status' => $this->status,
            'mobile' => $this->mobile,
            'whatsapp' => $this->whatsapp,
            'lat' => $this->lat,
            'lon' => $this->lon,
            // 'country_id' => $this->country_id,
            'privacy' => $this->privacy,
            'is_following' => $this->isFollowedBy(auth()->user()), // Am I following him?
            'user_permissions' => $ps,
        ];
    }
}
