<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $guarded = [];

    /**
     * roles inside the business
     */
    public function scopeBusinessAllowedPermissions($query)
    {
        return $query->whereIn('name', config('myconfig.business_permissions'));
    }

    /**
     * roles inside the business
     */
    public function scopeAppAllowedPermissions($query)
    {
        return $query->whereNotIn('name', config('myconfig.business_permissions'));
    }
}
