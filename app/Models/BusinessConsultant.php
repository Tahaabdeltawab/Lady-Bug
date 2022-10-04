<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessConsultant extends Model
{
    public $table = 'business_consultant';
    public $timestamps = false;

    public $fillable = [
        'role_user_id',
        'plan_id',
        'period',
    ];


    public function role_user()
    {
        return $this->belongsTo(RoleUser::class, 'role_user_id', 'id');
    }
}
