<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class FarmReport
 * @package App\Models
 * @version September 13, 2022, 7:32 pm EET
 *
 * @property \App\Models\Farm $farm
 * @property \App\Models\FarmedTypeStage $farmedTypeStage
 * @property integer $farm_id
 * @property integer $farmed_type_stage_id
 * @property string $lat
 * @property string $lon
 * @property string $fertilization_start_date
 * @property string $fertilization_unit
 * @property string $notes
 */
class FarmReport extends Model
{


    public $table = 'farm_reports';
    



    public $fillable = [
        'farm_id',
        'farmed_type_stage_id',
        'lat',
        'lon',
        'fertilization_start_date',
        'fertilization_unit',
        'notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'farm_id' => 'integer',
        'farmed_type_stage_id' => 'integer',
        'lat' => 'string',
        'lon' => 'string',
        'fertilization_start_date' => 'date',
        'fertilization_unit' => 'string',
        'notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'farm_id' => 'required',
        'farmed_type_stage_id' => 'nullable',
        'lat' => 'required',
        'lon' => 'required',
        'fertilization_start_date' => 'nullable',
        'fertilization_unit' => 'nullable',
        'notes' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farm()
    {
        return $this->belongsTo(\App\Models\Farm::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmedTypeStage()
    {
        return $this->belongsTo(\App\Models\FarmedTypeStage::class);
    }
}