<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class ConsultancyProfile
 * @package App\Models
 * @version August 28, 2022, 5:38 pm EET
 *
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $workFields
 * @property \Illuminate\Database\Eloquent\Collection $offlineConsultancyPlans
 * @property integer $user_id
 * @property integer $experience
 * @property boolean $ar
 * @property boolean $en
 * @property number $consultancy_price
 * @property number $month_consultancy_price
 * @property number $year_consultancy_price
 * @property boolean $free_consultancy_price
 */
class ConsultancyProfile extends Model
{


    public $table = 'consultancy_profiles';
    



    public $fillable = [
        'user_id',
        'experience',
        'ar',
        'en',
        'consultancy_price',
        'month_consultancy_price',
        'year_consultancy_price',
        'free_consultancy_price'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'experience' => 'integer',
        'ar' => 'boolean',
        'en' => 'boolean',
        'consultancy_price' => 'decimal:2',
        'month_consultancy_price' => 'decimal:2',
        'year_consultancy_price' => 'decimal:2',
        'free_consultancy_price' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'experience' => 'required',
        'ar' => 'required',
        'en' => 'required',
        'consultancy_price' => 'required',
        'month_consultancy_price' => 'required',
        'year_consultancy_price' => 'required',
        'free_consultancy_price' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function workFields()
    {
        return $this->belongsToMany(\App\Models\WorkField::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function offlineConsultancyPlans()
    {
        return $this->hasMany(\App\Models\OfflineConsultancyPlan::class);
    }
}
