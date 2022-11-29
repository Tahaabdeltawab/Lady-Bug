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

    /**
     * roles called for the dashboard
     */
    public function scopeAppAllowedRoles($query)
    {
        return $query->whereNotIn('name', config('myconfig.not_editable_roles'));
    }
}
