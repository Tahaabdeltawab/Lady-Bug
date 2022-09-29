<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Ac
 * @package App\Models
 * @version September 11, 2022, 6:03 pm EET
 *
 * @property \Illuminate\Database\Eloquent\Collection $insecticides
 * @property \Illuminate\Database\Eloquent\Collection $pathogens
 * @property string $name
 * @property string $who_class
 * @property integer $withdrawal_days
 * @property string $precautions
 */
class Ac extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name', 'precautions'];
	public $timestamps = false;

    public $table = 'acs';
    



    public $fillable = [
        'name',
        'who_class',
        'withdrawal_days',
        'precautions'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'who_class' => 'string',
        'withdrawal_days' => 'integer',
        'precautions' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'who_class' => 'nullable|in:organic,inorganic,rejected',
        'withdrawal_days' => 'nullable',
        'precautions' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function insecticides()
    {
        return $this->belongsToMany(\App\Models\Insecticide::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function pathogens()
    {
        return $this->belongsToMany(\App\Models\Pathogen::class);
    }
}
