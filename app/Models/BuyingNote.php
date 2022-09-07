<?php

namespace App\Models;

use Eloquent as Model;



class BuyingNote extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['content'];


    public $table = 'buying_notes';



    public $fillable = [
        'content'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'content' => 'string'
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
