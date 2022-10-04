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
}
