<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class FarmedTypeNutVal
 * @package App\Models
 * @version September 10, 2022, 1:52 pm EET
 *
 * @property number $farmed_type_id
 * @property number $calories
 * @property number $total_fat
 * @property number $sat_fat
 * @property number $cholesterol
 * @property number $na
 * @property number $k
 * @property number $total_carb
 * @property number $dietary_fiber
 * @property number $sugar
 * @property number $protein
 * @property number $v_c
 * @property number $fe
 * @property number $v_b6
 * @property number $mg
 * @property number $ca
 * @property number $v_d
 * @property number $cobalamin
 */
class FarmedTypeNutVal extends Model
{


    public $table = 'farmed_type_nut_vals';
    



    public $fillable = [
        'farmed_type_id',
        'calories',
        'total_fat',
        'sat_fat',
        'cholesterol',
        'na',
        'k',
        'total_carb',
        'dietary_fiber',
        'sugar',
        'protein',
        'v_c',
        'fe',
        'v_b6',
        'mg',
        'ca',
        'v_d',
        'cobalamin'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'farmed_type_id' => 'decimal:2',
        'calories' => 'decimal:2',
        'total_fat' => 'decimal:2',
        'sat_fat' => 'decimal:2',
        'cholesterol' => 'decimal:2',
        'na' => 'decimal:2',
        'k' => 'decimal:2',
        'total_carb' => 'decimal:2',
        'dietary_fiber' => 'decimal:2',
        'sugar' => 'decimal:2',
        'protein' => 'decimal:2',
        'v_c' => 'decimal:2',
        'fe' => 'decimal:2',
        'v_b6' => 'decimal:2',
        'mg' => 'decimal:2',
        'ca' => 'decimal:2',
        'v_d' => 'decimal:2',
        'cobalamin' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'farmed_type_id' => 'required',
        'calories' => 'nullable',
        'total_fat' => 'nullable',
        'sat_fat' => 'nullable',
        'cholesterol' => 'nullable',
        'na' => 'nullable',
        'k' => 'nullable',
        'total_carb' => 'nullable',
        'dietary_fiber' => 'nullable',
        'sugar' => 'nullable',
        'protein' => 'nullable',
        'v_c' => 'nullable',
        'fe' => 'nullable',
        'v_b6' => 'nullable',
        'mg' => 'nullable',
        'ca' => 'nullable',
        'v_d' => 'nullable',
        'cobalamin' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmed_type()
    {
        return $this->belongsTo(\App\Models\FarmedType::class);
    }
}
