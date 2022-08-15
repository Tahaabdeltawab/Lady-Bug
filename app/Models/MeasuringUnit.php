<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class MeasuringUnit extends Model implements TranslatableContract
{
    use /*SoftDeletes,*/ Translatable;

    public $translatedAttributes = ['name'];


    public $table = 'measuring_units';


    protected $dates = ['deleted_at'];



    public $fillable = [
        // 'name',
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
        // 'name' => 'string',
        'code' => 'string',
        'measurable' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_ar_localized' => 'required|max:200',
        'name_en_localized' => 'required|max:200',
        'code' => 'required|max:200',
        'measurable' => 'required|max:200'
    ];


}
