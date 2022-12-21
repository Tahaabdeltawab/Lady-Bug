<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessSmWebResource extends JsonResource
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
            'com_name' => $this->com_name,
            'description' => $this->description,
            'main_asset' => @$this->main_asset[0]->asset_url,
            'user_permissions' => $ps,
            'user_role' => @__(collect(auth()->user()->get_roles($this->id))->first()['name']),
            'privacy_permissions' => $this->privacyPermissions(),
            'can_see' => $this->canSee(),
        ];
    }
}
