<?php

namespace App\Models;

use Eloquent as Model;



class MeasuringUnit extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];
	public $timestamps = false;


    public $table = 'measuring_units';



    public $fillable = [
        'name',
        'code',
        'measurable'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
        'measurable' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name.ar' => 'required|max:30',
        'name.en' => 'required|max:30',
        'code' => 'required|max:30',
        'measurable' => 'required|max:30'
    ];


}
