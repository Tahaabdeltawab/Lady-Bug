<?php

namespace App\Models;

use Eloquent as Model;


class WeatherNote extends Model
{

    public $table = 'weather_notes';



    public $fillable = [
        'content',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'content' => 'string',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'content' => 'required',
        'user_id' => 'required'
    ];


}
