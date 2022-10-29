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
        'title.ar' => 'required|max:30',
        'title.en' => 'required|max:30',
        'content.ar' => 'required',
        'content.en' => 'required',
    ];


}
