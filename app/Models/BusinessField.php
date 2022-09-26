<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class BusinessField
 * @package App\Models
 * @version September 10, 2022, 4:44 pm EET
 *
 * @property string $name
 */
class BusinessField extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];

    public $table = 'business_fields';
	public $timestamps = false;
    



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
