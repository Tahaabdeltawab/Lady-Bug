<?php

namespace App\Models;

use App\Traits\Followable;
use App\Traits\Follower;

class Business extends Team
{
    use Follower, Followable;

    public $table = 'businesses';




    public $fillable = [
        'user_id',
        'business_field_id',
        'description',
        'com_name',
        'status',
        'mobile',
        'whatsapp',
        'lat',
        'lon',
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
        'lat' => 'decimal:2',
        'lon' => 'decimal:2',
        'country_id' => 'integer',
        'privacy' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'business_field_id' => 'required',
        'description' => 'nullable',
        'main_asset' => 'nullable',
        'cover_asset' => 'nullable',
        'com_name' => 'required',
        'status' => 'required',
        'mobile' => 'nullable',
        'whatsapp' => 'nullable',
        'lat' => 'nullable',
        'lon' => 'nullable',
        'country_id' => 'nullable',
        'privacy' => 'nullable',
        'branches' => 'nullable|array',
        'agents' => 'nullable|array',
        'agents.*' => 'exists:businesses,id',
        'distributors' => 'nullable|array',
        'distributors.*' => 'exists:businesses,id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
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

    public function scopePublic($q)
    {
        return $q->where('privacy', 1);
    }
    public function scopePrivate($q)
    {
        return $q->where('privacy', 0);
    }
}
