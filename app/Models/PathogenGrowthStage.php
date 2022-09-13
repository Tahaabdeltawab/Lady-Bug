<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class PathogenGrowthStage
 * @package App\Models
 * @version September 9, 2022, 7:34 pm EET
 *
 * @property string $name
 */
class PathogenGrowthStage extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];
	public $timestamps = false;

    public $table = 'pathogen_growth_stages';
    



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
