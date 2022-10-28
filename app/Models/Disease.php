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
        'name.ar' => 'required|max:30',
        'name.en' => 'required|max:30',
        'description.ar' => 'required|max:255',
        'description.en' => 'required|max:255',
        'countries' => 'nullable|array',
        'countries.*' => 'exists:countries,id',
        'pathogens' => 'nullable|array',
        'pathogens.*' => 'exists:pathogens,id',
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
     * Get the factors that help in Disease spread
     */
    public function causative()
    {
        return $this->hasOne(DiseaseCausative::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function countries()
    {
        return $this->belongsToMany(\App\Models\Country::class);
    }

    /**
     * this relation returns multiple records of the same farmed types due to presence of multiple stages in the same farmed type
     * i.e. multiple records in sensitive_disease_farmed_type table
     */
    public function sensitive_farmed_types_stages()
    {
        return $this->belongsToMany(FarmedType::class, 'sensitive_disease_farmed_type')->withPivot('farmed_type_stage_id');
    }

    /**
     * returns unique farmed types that are sensitive to a particular disease
     */
    public function sensitive_farmed_types()
    {
        return $this->belongsToMany(FarmedType::class, 'sensitive_disease_farmed_type')->groupBy('farmed_types.id');
    }

    public function resistant_farmed_types()
    {
        return $this->belongsToMany(FarmedType::class, 'resistant_disease_farmed_type');
    }

}
