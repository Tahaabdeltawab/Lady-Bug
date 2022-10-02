<?php
//overridden by Entities\User
namespace App\Models;

use App\Traits\Followable;
use App\Traits\Follower;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use willvincent\Rateable\Rateable;
use Overtrue\LaravelLike\Traits\Liker;
use Laratrust\Traits\LaratrustUserTrait;


class User extends Authenticatable implements JWTSubject
{
    use LaratrustUserTrait, HasFactory, Notifiable, Follower, Followable, Rateable, Liker;
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'status',
        'block_duration',
        'is_notifiable',
        'activity_points',
        'email_verified',
        'mobile_verified',
        'human_job_id',
        'password',
        'income',
        'city_id',
        'dob',
        'bio',
        'balance',
        'marital_status',
        'is_consultant',
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

    public function get_roles($farm_id=null)
    {
        return $this->roles->filter(function ($role) use($farm_id) {
            return $role['pivot'][config('laratrust.foreign_keys.team')] == $farm_id;
        })->map->only(['id', 'name'])->toArray();
    }


   /*  public function farms()
    {
        return $this->morphedByMany(Farm::class, 'workable', 'workables', 'worker_id', 'workable_id')->using(Workable::class)->withPivot('id', 'status')->withTimestamps();
    } */

    public function favorites()
    {
        return $this->morphedByMany(FarmedType::class, 'favoriteable', 'favorites', 'user_id', 'favoriteable_id');//->withTimestamps();
    }


    public function scopeAdmin($q)
    {
        return $q->where('type', 'app_admin');
    }

    public function scopeUser($q)
    {
        return $q->where('type', 'app_user');
    }

    public function scopeAccepted($q)
    {
        return $q->where('status', 'accepted');
    }

    public function scopeBlocked($q)
    {
        return $q->where('status', 'blocked');
    }

    public function scopeCons($q)
    {
        return $q->where('is_consultant', 1);
    }

    public function asset()
    {
        return $this->morphOne(Asset::class, 'assetable');
    }

    public function job()
    {
        return $this->belongsTo(HumanJob::class, 'human_job_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function consultancyProfile()
    {
        return $this->hasOne(ConsultancyProfile::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }
    public function careers()
    {
        return $this->hasMany(Career::class);
    }
    public function residences()
    {
        return $this->hasMany(Residence::class);
    }
    public function visiteds()
    {
        return $this->hasMany(Visited::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function farms()
    {
        return $this->hasMany(Farm::class, 'admin_id');
    }

    public function farmReports()
    {
        return $this->hasMany(FarmReport::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, FarmReport::class);
    }

    public function allBusinesses()
    {
        return $this->rolesTeams()->where(function($q){
            $q->where([['role_user.start_date', '<=', today()],['role_user.end_date', '>', today()]])
            ->orWhere([['role_user.start_date','=', null], ['role_user.end_date','=', null]]);
            /**
             * * DB Note
             * ->orWhere(['role_user.start_date' => null, 'role_user.end_date' => null]);
             * will produce
             * or (`role_user`.`start_dates` is null or `role_user`.`end_dates` is null)"
             * while the first will produce
             * or (`role_user`.`start_dates` is null and `role_user`.`end_dates` is null)"
            **/

        });
    }

    public function ownBusinesses()
    {
        return $this->hasMany(Business::class);
    }

    public function hasBusiness($id)
    {
        return $this->hasMany(Business::class)->where('businesses.id', $id)->exists();
    }

    public function sharedBusinesses($ownids = null)
    {
        $own_ids = $ownids ?? $this->ownBusinesses()->pluck('id');
        return $this->allBusinesses()->whereNotIn('businesses.id', $own_ids);
    }

    public function followedBusinesses()
    {
        return $this->morphedByMany(Business::class, 'followable');
    }

    public function followedUsers()
    {
        return $this->morphedByMany(self::class, 'followable');
    }



    public static function generate_code($length = 6)
    {
        $characters       = '0123456789';
        $charactersLength = strlen( $characters );
        $code            = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $code .= $characters[ rand( 0, $charactersLength - 1 ) ];
        }
        return $code;
    }

}
