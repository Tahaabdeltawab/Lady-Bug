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
 * @property string $main_img
 * @property string $cover_img
 * @property string $com_name
 * @property string $status
 * @property string $mobile
 * @property string $whatsapp
 * @property number $lat
 * @property number $lon
 * @property integer $country_id
 * @property boolean $privacy
 */
class Business extends Model
{


    public $table = 'businesses';
    



    public $fillable = [
        'user_id',
        'business_field_id',
        'description',
        'main_img',
        'cover_img',
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
        'main_img' => 'string',
        'cover_img' => 'string',
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
        'main_img' => 'nullable',
        'cover_img' => 'nullable',
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
}
