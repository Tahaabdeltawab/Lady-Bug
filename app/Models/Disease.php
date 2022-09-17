<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Disease
 * @package App\Models
 * @version September 11, 2022, 6:13 pm EET
 *
 * @property \Illuminate\Database\Eloquent\Collection $farmedTypes
 * @property \Illuminate\Database\Eloquent\Collection $farms
 * @property \Illuminate\Database\Eloquent\Collection $pathogens
 * @property \Illuminate\Database\Eloquent\Collection $countries
 * @property string $name
 * @property string $description
 */
class Disease extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name', 'description'];
	public $timestamps = false;

    public $table = 'diseases';
    



    public $fillable = [
        'name',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'description' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function farmedTypes()
    {
        return $this->belongsToMany(\App\Models\FarmedType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function farms()
    {
        return $this->belongsToMany(\App\Models\Farm::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function pathogens()
    {
        return $this->belongsToMany(\App\Models\Pathogen::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function countries()
    {
        return $this->belongsToMany(\App\Models\Country::class);
    }
}
