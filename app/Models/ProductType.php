<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class ProductType
 * @package App\Models
 * @version September 17, 2022, 6:30 pm EET
 *
 * @property string $name
 */
class ProductType extends Model
{
    use \App\Traits\SpatieHasTranslations;

    public $translatable = ['name'];
	public $timestamps = false;

    public $table = 'product_types';
    



    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    
}
