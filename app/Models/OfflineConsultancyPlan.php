<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class OfflineConsultancyPlan
 * @package App\Models
 * @version August 28, 2022, 5:55 pm EET
 *
 * @property \App\Models\ConsultancyProfile $consultancyProfile
 * @property integer $consultancy_profile_id
 * @property string $address
 * @property string $date
 * @property number $visit_price
 * @property number $year_price
 * @property number $two_year_price
 */
class OfflineConsultancyPlan extends Model
{


    public $table = 'offline_consultancy_plans';
    



    public $fillable = [
        'consultancy_profile_id',
        'address',
        'date',
        'visit_price',
        'year_price',
        'two_year_price'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'consultancy_profile_id' => 'integer',
        'address' => 'string',
        'date' => 'date',
        'visit_price' => 'decimal:2',
        'year_price' => 'decimal:2',
        'two_year_price' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'consultancy_profile_id' => 'required',
        'address' => 'required',
        'date' => 'required',
        'visit_price' => 'required',
        'year_price' => 'required',
        'two_year_price' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function consultancyProfile()
    {
        return $this->belongsTo(\App\Models\ConsultancyProfile::class);
    }
}
