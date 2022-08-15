<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class BuyingNote extends Model implements TranslatableContract
{
    use /*SoftDeletes,*/ Translatable;

    public $translatedAttributes = ['content'];


    public $table = 'buying_notes';


    protected $dates = ['deleted_at'];



    public $fillable = [
        // 'content'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'content' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'content_ar_localized' => 'required',
        'content_en_localized' => 'required',
    ];


}
