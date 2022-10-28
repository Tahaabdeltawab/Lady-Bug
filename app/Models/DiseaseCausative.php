<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class DiseaseCausative
 * @package App\Models
 * @version September 11, 2022, 6:16 pm EET
 *
 * @property \App\Models\Disease $disease
 * @property integer $disease_id
 * @property number $temp_gt
 * @property number $temp_lt
 * @property number $humidity_gt
 * @property number $humidity_lt
 * @property number $ph_gt
 * @property number $ph_lt
 * @property number $soil_salts_gt
 * @property number $soil_salts_lt
 * @property number $water_salts_gt
 * @property number $water_salts_lt
 */
class DiseaseCausative extends Model
{


    public $table = 'disease_causatives';
	public $timestamps = false;




    public $fillable = [
        'disease_id',
        'temp_gt',
        'temp_lt',
        'humidity_gt',
        'humidity_lt',
        'ph_gt',
        'ph_lt',
        'soil_salts_gt',
        'soil_salts_lt',
        'water_salts_gt',
        'water_salts_lt',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'disease_id' => 'integer',
        'temp_gt' => 'decimal:2',
        'temp_lt' => 'decimal:2',
        'humidity_gt' => 'decimal:2',
        'humidity_lt' => 'decimal:2',
        'ph_gt' => 'decimal:2',
        'ph_lt' => 'decimal:2',
        'soil_salts_gt' => 'decimal:2',
        'soil_salts_lt' => 'decimal:2',
        'water_salts_gt' => 'decimal:2',
        'water_salts_lt' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'disease_id' => 'required|exists:diseases,id',
        'temp_gt' => 'nullable|numeric',
        'temp_lt' => 'nullable|numeric',
        'humidity_gt' => 'nullable|numeric',
        'humidity_lt' => 'nullable|numeric',
        'ph_gt' => 'nullable|numeric|max:14|min:0',
        'ph_lt' => 'nullable|numeric|max:14|min:0',
        'soil_salts_gt' => 'nullable|numeric',
        'soil_salts_lt' => 'nullable|numeric',
        'water_salts_gt' => 'nullable|numeric',
        'water_salts_lt' => 'nullable|numeric'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function disease()
    {
        return $this->belongsTo(\App\Models\Disease::class);
    }
}
