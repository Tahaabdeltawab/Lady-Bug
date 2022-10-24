<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class NutElemValue
 * @package App\Models
 * @version September 10, 2022, 1:45 pm EET
 *
 * @property number $n
 * @property number $p
 * @property number $k
 * @property number $fe
 * @property number $b
 * @property number $ca
 * @property number $mg
 */
class NutElemValue extends Model
{


    public $table = 'nut_elem_values';
	public $timestamps = false;




    public $fillable = [
        'n',
        'p',
        'k',
        'fe',
        'b',
        'ca',
        'mg'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'n' => 'decimal:2',
        'p' => 'decimal:2',
        'k' => 'decimal:2',
        'fe' => 'decimal:2',
        'b' => 'decimal:2',
        'ca' => 'decimal:2',
        'mg' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'n' => 'nullable|numeric',
        'p' => 'nullable|numeric',
        'k' => 'nullable|numeric',
        'fe' => 'nullable|numeric',
        'b' => 'nullable|numeric',
        'ca' => 'nullable|numeric',
        'mg' => 'nullable|numeric'
    ];


}
