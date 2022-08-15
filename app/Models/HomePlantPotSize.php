<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class HomePlantPotSize extends Model
{
  // use SoftDeletes;


    public $table = 'home_plant_pot_sizes';


    protected $dates = ['deleted_at'];



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
