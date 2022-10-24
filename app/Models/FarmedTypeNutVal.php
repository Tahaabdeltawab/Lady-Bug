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
	public $timestamps = false;




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
        'farmed_type_id' => 'integer',
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
        'farmed_type_id' => 'required|exists:farmed_types,id',
        'calories' => 'nullable|numeric',
        'total_fat' => 'nullable|numeric',
        'sat_fat' => 'nullable|numeric',
        'cholesterol' => 'nullable|numeric',
        'na' => 'nullable|numeric',
        'k' => 'nullable|numeric',
        'total_carb' => 'nullable|numeric',
        'dietary_fiber' => 'nullable|numeric',
        'sugar' => 'nullable|numeric',
        'protein' => 'nullable|numeric',
        'v_c' => 'nullable|numeric',
        'fe' => 'nullable|numeric',
        'v_b6' => 'nullable|numeric',
        'mg' => 'nullable|numeric',
        'ca' => 'nullable|numeric',
        'v_d' => 'nullable|numeric',
        'cobalamin' => 'nullable|numeric'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmedType()
    {
        return $this->belongsTo(\App\Models\FarmedType::class);
    }
}
