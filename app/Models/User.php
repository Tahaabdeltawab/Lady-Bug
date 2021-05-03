<?php
//overridden by Entities\User
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Overtrue\LaravelFollow\Followable;
use willvincent\Rateable\Rateable;
use Overtrue\LaravelLike\Traits\Liker;
use Overtrue\LaravelSubscribe\Traits\Subscriber;
use Laratrust\Traits\LaratrustUserTrait;


class User extends Authenticatable implements JWTSubject
{
    use LaratrustUserTrait, HasFactory, Notifiable, Followable, Rateable, Liker, Subscriber;
    // use SoftDeletes;


    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'status',
        'is_notifiable',
        'activity_points',
        'email_verified',
        'mobile_verified',
        'human_job_id',
        // 'photo_id',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_notifiable' => 'boolean',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }

   /*  public function farms()
    {
        return $this->morphedByMany(Farm::class, 'workable', 'workables', 'worker_id', 'workable_id')->using(Workable::class)->withPivot('id', 'status')->withTimestamps();
    } */

    public function favorites()
    {
        return $this->morphedByMany(FarmedType::class, 'favoriteable', 'favorites', 'user_id', 'favoriteable_id');//->withTimestamps();
    }


    public function asset()
    {
        return $this->morphOne(Asset::class, 'assetable');
    }

    public function job()
    {
        return $this->belongsTo(HumanJob::class, 'human_job_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }


}
