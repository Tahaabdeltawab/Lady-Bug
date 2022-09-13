<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class FarmedTypeFertilizationNeed
 * @package App\Models
 * @version September 10, 2022, 2:09 pm EET
 *
 * @property \App\Models\FarmedType $farmedType
 * @property \App\Models\NutElemValue $nutElemValue
 * @property integer $farmed_type_id
 * @property string $stage
 * @property string $per
 * @property integer $nut_elem_value_id
 */
class FarmedTypeFertilizationNeed extends Model
{


    public $table = 'farmed_type_fertilization_needs';
    



    public $fillable = [
        'farmed_type_id',
        'stage',
        'per',
        'nut_elem_value_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'farmed_type_id' => 'integer',
        'stage' => 'string',
        'per' => 'string',
        'nut_elem_value_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'farmed_type_id' => 'required',
        'stage' => 'nullable',
        'per' => 'nullable',
        'nut_elem_value_id' => 'required'
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
    public function nutElemValue()
    {
        return $this->belongsTo(\App\Models\NutElemValue::class);
    }
}
