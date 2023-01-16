<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{

    public $table = 'role_user';
    public $timestamps = false;

    public $fillable = [
        'role_id',
        'user_id',
        'user_type',
        'business_id',
        'start_date',
        'end_date',
        'active',
    ];


    public function plan()
    {
        return $this->hasOne(BusinessConsultant::class, 'role_user_id', 'id');
    }

    /**
     * permissions related to this user business role
     */
    public function permissions()
    {
        return $this->hasMany(PermissionUser::class, 'user_id', 'user_id')
        ->where('user_type', User::class)
        ->where('business_id', $this->business_id);
    }

    public function scopeEnded($q)
    {
        return $q->where('user_type', User::class)
        ->whereNotNull('business_id')->whereNotNull('end_date')
        ->where('end_date', '<', today());
    }
}
