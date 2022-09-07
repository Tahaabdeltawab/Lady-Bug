<?php

namespace App\Models;

use Eloquent as Model;

class RatingQuestion extends Model
{
    use \App\Traits\SpatieHasTranslations;

    public $table = 'rating_questions';
    public $translatable = ['name', 'description'];
    public $timestamps = false;



    public $fillable = [
        'name',
        'description',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'name' => 'string',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'nullable'
    ];

    
}
