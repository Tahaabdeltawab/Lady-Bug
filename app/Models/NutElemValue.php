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
        'mg',
        's',
        'zn',
        'mn',
        'cu',
        'cl',
        'mo',
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
        'mg' => 'decimal:2',
        's' => 'decimal:2',
        'zn' => 'decimal:2',
        'mn' => 'decimal:2',
        'cu' => 'decimal:2',
        'cl' => 'decimal:2',
        'mo' => 'decimal:2',
    ];

    /**
     * Validation rules
     * نسب مئوية
     * @var array
     */
    public static $rules = [
        'n' => 'nullable|numeric|min:0|max:100',
        'p' => 'nullable|numeric|min:0|max:100',
        'k' => 'nullable|numeric|min:0|max:100',
        'fe' => 'nullable|numeric|min:0|max:100',
        'b' => 'nullable|numeric|min:0|max:100',
        'ca' => 'nullable|numeric|min:0|max:100',
        'mg' => 'nullable|numeric|min:0|max:100',
        's' => 'nullable|numeric|min:0|max:100',
        'zn' => 'nullable|numeric|min:0|max:100',
        'mn' => 'nullable|numeric|min:0|max:100',
        'cu' => 'nullable|numeric|min:0|max:100',
        'cl' => 'nullable|numeric|min:0|max:100',
        'mo' => 'nullable|numeric|min:0|max:100',
    ];

    /**
     * Get the fertilizer associated with the NutElemValue
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fertilizer()
    {
        return $this->hasOne(Fertilizer::class);
    }

}
