<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Information extends Model implements TranslatableContract
{
    use /*SoftDeletes,*/ Translatable;

    public $translatedAttributes = ['title', 'content'];


    public $table = 'information';


    protected $dates = ['deleted_at'];



    public $fillable = [
        // 'title',
        // 'content'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'title' => 'string',
        // 'content' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title_ar_localized' => 'required|max:200',
        'title_en_localized' => 'required|max:200',
        'content_ar_localized' => 'required',
        'content_en_localized' => 'required',
        // 'title' => 'required|max:200',
        // 'content' => 'required'
    ];


}
