<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Country
 * @package App\Models
 * @version September 10, 2022, 2:46 am EET
 *
 * @property integer $name
 */
class Country extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];
	public $timestamps = false;

    public $table = 'countries';
    



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
