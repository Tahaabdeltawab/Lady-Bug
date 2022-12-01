<?php

namespace App\Models;

use App\Traits\Followable;
use App\Traits\Follower;
use App\Traits\Rateable;

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
        'privacy' => 'boolean'
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
        'privacy' => 'nullable',
        'branches' => 'nullable|array',
        'agents' => 'nullable|array',
        'agents.*' => 'exists:businesses,id',
        'distributors' => 'nullable|array',
        'distributors.*' => 'exists:businesses,id',
    ];


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

    public function scopePublic($q)
    {
        return $q->where('privacy', 1);
    }
    public function scopePrivate($q)
    {
        return $q->where('privacy', 0);
    }
}
