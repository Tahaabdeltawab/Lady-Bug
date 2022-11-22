<?php

namespace App\Models;

use Eloquent as Model;


class Location extends Model
{

    public $table = 'locations';



    public $fillable = [
        'latitude',
        'longitude',
        'country',
        'city',
        'district',
        'postal',
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
        'postal' => 'string',
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
