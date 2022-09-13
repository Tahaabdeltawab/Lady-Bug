<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class InfectionSpreadStage
 * @package App\Models
 * @version September 9, 2022, 7:33 pm EET
 *
 * @property string $name
 */
class InfectionSpreadStage extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];
	public $timestamps = false;

    public $table = 'infection_spread_stages';
    



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
