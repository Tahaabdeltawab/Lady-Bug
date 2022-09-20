<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Business
 * @package App\Models
 * @version September 10, 2022, 5:36 pm EET
 *
 * @property \Illuminate\Database\Eloquent\Collection $farms
 * @property \App\Models\User $user
 * @property \App\Models\BusinessField $businessField
 * @property \App\Models\Country $country
 * @property integer $user_id
 * @property integer $business_field_id
 * @property string $description
 * @property string $com_name
 * @property string $status
 * @property string $mobile
 * @property string $whatsapp
 * @property number $lat
 * @property number $lon
 * @property integer $country_id
 * @property boolean $privacy
 */
class Business extends Team
{


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
        'privacy' => 'nullable'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function agents()
    {
        return $this->belongsToMany(self::class, 'business_dealer', 'business_id', 'dealer_id')->where('type', 'agent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function distributors()
    {
        return $this->belongsToMany(self::class, 'business_dealer', 'business_id', 'dealer_id')->where('type', 'distributor');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function main_asset()
    {
        return $this->assets()->where('asset_name', 'like', 'business-main%')->first();
    }

    public function cover_asset()
    {
        return $this->assets()->where('asset_name', 'like', 'business-cover%')->first();
    }
}
