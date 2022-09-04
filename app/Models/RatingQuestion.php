<?php

namespace App\Models;

use Eloquent as Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


/**
 * Class RatingQuestion
 * @package App\Models
 * @version August 15, 2022, 6:13 pm EET
 *
 * @property string $type
 */
class RatingQuestion extends Model implements TranslatableContract
{
    use Translatable;

    public $table = 'rating_questions';
    public $translatedAttributes = ['name', 'description'];




    public $fillable = [
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'string'
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
