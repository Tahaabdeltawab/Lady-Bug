<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SaltDetail extends Model
{
  // use SoftDeletes;


    public $table = 'salt_details';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'saltable_type',
        'PH',
        'CO3',
        'HCO3',
        'Cl',
        'SO4',
        'Ca',
        'Mg',
        'K',
        'Na',
        'Na2CO3'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'saltable_type' => 'string',
        'PH' => 'double',
        'CO3' => 'double',
        'HCO3' => 'double',
        'Cl' => 'double',
        'SO4' => 'double',
        'Ca' => 'double',
        'Mg' => 'double',
        'K' => 'double',
        'Na' => 'double',
        'Na2CO3' => 'double'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'saltable_type' => 'required',
        'PH' => 'required',
        'CO3' => 'required',
        'HCO3' => 'required',
        'Cl' => 'required',
        'SO4' => 'required',
        'Ca' => 'required',
        'Mg' => 'required',
        'K' => 'required',
        'Na' => 'required',
        'Na2CO3' => 'required'
    ];


}
