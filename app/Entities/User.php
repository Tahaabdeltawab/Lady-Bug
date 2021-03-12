<?php

namespace App\Entities;
use Mekaeil\LaravelUserManagement\Entities\User as UserManagement;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Overtrue\LaravelFollow\Followable;
use Overtrue\LaravelFavorite\Traits\Favoriter;
use Overtrue\LaravelLike\Traits\Liker;
use Overtrue\LaravelSubscribe\Traits\Subscriber;

class User extends UserManagement implements JWTSubject
{
    use HasFactory, Followable, Favoriter, Liker, Subscriber, SoftDeletes;

    protected $guard_name =  'web';
    
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'status',           // 'pending','accepted','blocked' | DEFAULT: pending
        'email_verified',
        'mobile_verified',        
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];  

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

 
    public function getJWTCustomClaims()
    {
        return [];
    }

    
    public function farms(){
        return $this->morphedByMany(Farm::class, 'workable', 'workables', 'worker_id', 'workable_id')->using(Workable::class)->withPivot('id', 'status')->withTimestamps();
    }


}

