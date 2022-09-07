<?php

namespace App\Models;

use Eloquent as Model;


class HomePlantPotSize extends Model
{

    public $table = 'home_plant_pot_sizes';



    public $fillable = [
        'size'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'size' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'size' => 'required|integer'
    ];


}
