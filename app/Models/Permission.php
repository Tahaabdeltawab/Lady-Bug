<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $guarded = [];

    public function scopeBusinessAllowed($query)
    {
        return $query->whereIn('name', config('myconfig.business_permissions'));
    }
}
