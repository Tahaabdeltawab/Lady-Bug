<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Location extends Model
{
  // use SoftDeletes;


    public $table = 'locations';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'latitude',
        'longitude',
        'country',
        'city',
        'district',
        'details'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
        'country' => 'string',
        'city' => 'string',
        'district' => 'string',
        'details' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
