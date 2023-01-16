<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionUser extends Model
{

    public $table = 'permission_user';
    public $timestamps = false;

    public $fillable = [
        'permission_id',
        'user_id',
        'user_type',
        'business_id',
    ];


    public function roleUser()
    {
        return $this->hasOne(RoleUser::class, 'role_user_id', 'id');
    }
    /**
     * role related to this user business permissions
     */
    public function role()
    {
        return $this->belongsTo(RoleUser::class, 'user_id', 'user_id')
        ->where('user_type', User::class)
        ->where('business_id', $this->business_id);
    }

    public function scopeEnded($q)
    {
        return $q->where('user_type', 'App\Models\User')
        ->whereNotNull('business_id')->whereNotNull('end_date')
        ->where('end_date', '<', today());
    }
}
