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
        'name.ar' => 'required|max:200',
        'name.en' => 'required|max:200',
        'code' => 'required|max:200',
        'measurable' => 'required|max:200'
    ];


}
