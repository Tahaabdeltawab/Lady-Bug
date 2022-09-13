<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class FarmedTypeExtras
 * @package App\Models
 * @version September 11, 2022, 4:02 pm EET
 *
 * @property \App\Models\FarmedType $farmedType
 * @property \App\Models\IrrigationRate $irrigationRate
 * @property integer $farmed_type_id
 * @property integer $irrigation_rate_id
 * @property string $seedling_type
 * @property string $scientific_name
 * @property string $history
 * @property string $producer
 * @property string $description
 * @property integer $cold_hours
 * @property integer $illumination_hours
 * @property number $seeds_rate
 * @property number $production_rate
 */
class FarmedTypeExtras extends Model
{


    public $table = 'farmed_type_extras';
    



    public $fillable = [
        'farmed_type_id',
        'irrigation_rate_id',
        'seedling_type',
        'scientific_name',
        'history',
        'producer',
        'description',
        'cold_hours',
        'illumination_hours',
        'seeds_rate',
        'production_rate'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'farmed_type_id' => 'integer',
        'irrigation_rate_id' => 'integer',
        'seedling_type' => 'string',
        'scientific_name' => 'string',
        'history' => 'string',
        'producer' => 'string',
        'description' => 'string',
        'cold_hours' => 'integer',
        'illumination_hours' => 'integer',
        'seeds_rate' => 'decimal:2',
        'production_rate' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'farmed_type_id' => 'required',
        'irrigation_rate_id' => 'nullable',
        'seedling_type' => 'nullable',
        'scientific_name' => 'nullable',
        'history' => 'nullable',
        'producer' => 'nullable',
        'description' => 'nullable',
        'cold_hours' => 'nullable',
        'illumination_hours' => 'nullable',
        'seeds_rate' => 'nullable',
        'production_rate' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmedType()
    {
        return $this->belongsTo(\App\Models\FarmedType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function irrigationRate()
    {
        return $this->belongsTo(\App\Models\IrrigationRate::class);
    }
}
