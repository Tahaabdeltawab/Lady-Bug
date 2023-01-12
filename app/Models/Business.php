<?php

namespace App\Models;

use App\Traits\Followable;
use App\Traits\Follower;
use App\Traits\Rateable;
use Illuminate\Support\Facades\DB;

class Business extends Team
{
    use Follower, Followable, Rateable;

    public $table = 'businesses';




    public $fillable = [
        'user_id',
        'business_field_id',
        'description',
        'com_name',
        'status',
        'mobile',
        'whatsapp',
        'location_id',
        'country_id',
        'privacy'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'business_field_id' => 'integer',
        'description' => 'string',
        'com_name' => 'string',
        'status' => 'string',
        'mobile' => 'string',
        'whatsapp' => 'string',
        'location_id' => 'integer',
        'country_id' => 'integer',
        'privacy' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'business_field_id' => 'required|exists:business_fields,id',
        'description' => 'nullable',
        'main_asset' => 'nullable|max:5000|image',
        'cover_asset' => 'nullable|max:5000|image',
        'com_name' => 'required',
        'status' => 'required',
        'mobile' => 'nullable',
        'whatsapp' => 'nullable',
        'location' => 'array',
        'location.latitude' => 'required',
        'location.longitude' => 'required',
        'location.country' => 'nullable',
        'location.city' => 'nullable',
        'location.district' => 'nullable',
        'location.details' => 'nullable',
        'location.postal' => 'nullable',
        'country_id' => 'nullable|exists:countries,id',
        'privacy' => 'required|in:0,1,2,3',
        'branches' => 'nullable|array',
        'agents' => 'nullable|array',
        'agents.*' => 'exists:businesses,id',
        'distributors' => 'nullable|array',
        'distributors.*' => 'exists:businesses,id',
    ];

    // PRIVACIES
    const GUIDE_P = 0;
    const PUBLIC_P = 1;
    const PRIVATE_P = 2;
    const SECRET_P = 3;

    // GETTERS

    public function getComNameAttribute($value){
        return @$this->businessField->name . ' ' . $value;
    }


    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function farms()
    {
        return $this->hasMany(\App\Models\Farm::class);
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class);
    }

    public function parts()
    {
        return $this->hasMany(\App\Models\BusinessPart::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function businessField()
    {
        return $this->belongsTo(\App\Models\BusinessField::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }

    /**
     * * الفروع
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branches()
    {
        return $this->hasMany(BusinessBranch::class);
    }


    /**
     * * الوكلاء
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function agents()
    {
        return $this->belongsToMany(self::class, 'business_dealer', 'business_id', 'dealer_id')->wherePivot('type', 'agent');
    }

    /**
     * * الموزعين
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function distributors()
    {
        return $this->belongsToMany(self::class, 'business_dealer', 'business_id', 'dealer_id')->wherePivot('type', 'distributor');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function main_asset()
    {
        return $this->assets()->where('asset_name', 'like', 'business-main%');
    }

    public function cover_asset()
    {
        return $this->assets()->where('asset_name', 'like', 'business-cover%');
    }

    public static $used_business_fields = [1,2,3,4,5];
    public function scopeFarm($q)
    {
        return $q->where('business_field_id', 1);
    }
    public function scopeInsecticide($q)
    {
        return $q->where('business_field_id', 2);
    }
    public function scopeFertilizer($q)
    {
        return $q->where('business_field_id', 3);
    }
    public function scopeFodder($q)
    {
        return $q->where('business_field_id', 4);
    }
    public function scopeVetmed($q)
    {
        return $q->where('business_field_id', 5);
    }

    public function scopeGuide($q)
    {
        return $q->where('privacy', self::GUIDE_P);
    }
    public function scopePublic($q)
    {
        return $q->where('privacy', self::PUBLIC_P);
    }
    public function scopePrivate($q)
    {
        return $q->where('privacy', self::PRIVATE_P);
    }
    public function scopeSecret($q)
    {
        return $q->where('privacy', self::SECRET_P);
    }

    public function isGuide()
    {
        return $this->privacy == self::GUIDE_P;
    }
    public function isPublic()
    {
        return $this->privacy == self::PUBLIC_P;
    }
    public function isPrivate()
    {
        return $this->privacy == self::PRIVATE_P;
    }
    public function isSecret()
    {
        return $this->privacy == self::SECRET_P;
    }

    /**
     * business participants who are following this business
     * business participants are by default followers to the business unless they unfollowed it
     */
    public function following_participants(){
        return $this->users->intersect($this->followers);
        // $users_ids = \DB::table('users')
        // ->join('role_user', 'role_user.user_id', 'users.id')
        // ->join('followables', 'followables.followable_id', 'role_user.business_id')
        // ->where('role_user.business_id', $this->id) // 3,2
        // ->where('followables.followable_type', self::class)
        // ->where('followables.followable_id', $this->id) // 2
        // ->pluck('users.id');
        // return User::where('id', $users_ids)->get();

    }
    public function privacyPermissions(User $user = null){
        $user = $user ?? auth()->user();
        if($user->get_roles($this->id)){
            return $this->privacy_permissions['all'];
        }
        return $this->privacy_permissions[$this->privacy];
    }

    public function userCan(string $permission, User $user = null){
        $user = $user ?? auth()->user();
        return in_array($permission, $this->privacyPermissions($user))
            || in_array($permission, $this->userPermissions($user->id));
    }

    public function canBeSeen(){
        return !empty($this->privacyPermissions());
    }

    /**
     * 'show-posts', // + videos, articles, stories
     * 'show-participants', // + goals, steps
     */
    public $privacy_permissions = [
        'all' => [
            'show-posts',
            'show-products',
            'show-farms',
            'show-participants',
            'show-reports',
        ],
        self::GUIDE_P => [
            'show-posts',
            'show-products',
            'show-farms',
            'show-participants',
            'show-reports',
        ],
        self::PUBLIC_P => [
            'show-posts',
            'show-products',
            'show-farms',
        ],
        self::PRIVATE_P => [
            'show-posts',
            'show-products',
        ],
        self::SECRET_P => [],
    ];

    public function userPermissions($user_id = null){
        $user_id = $user_id ?? auth()->id();
        return DB::table('permissions')->join('permission_user', 'permissions.id', 'permission_user.permission_id')
                ->where('business_id', $this->id)
                ->where('user_id', $user_id)
                ->pluck('permissions.name')->toArray();
    }
}
