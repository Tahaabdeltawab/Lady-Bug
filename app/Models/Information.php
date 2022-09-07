<?php

namespace App\Models;

use Eloquent as Model;



class Information extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['title', 'content'];


    public $table = 'information';



    public $fillable = [
        'title',
        'content'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'content' => 'string'
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
