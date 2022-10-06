<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];

    public function scopeBusinessAllowedRoles($query)
    {
        return $query->whereIn('name', config('myconfig.business_roles'));
    }

    public function scopeAppAllowedRoles($query) // roles called for the dashboard
    {
        return $query->whereNotIn('name', config('myconfig.business_roles'))->where('name', '!=', config('myconfig.user_default_role'));
    }
}
