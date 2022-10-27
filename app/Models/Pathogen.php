<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Pathogen
 * @package App\Models
 * @version September 11, 2022, 6:23 pm EET
 *
 * @property \Illuminate\Database\Eloquent\Collection $acs
 * @property \Illuminate\Database\Eloquent\Collection $diseases
 * @property \Illuminate\Database\Eloquent\Collection $pathogenGrowthStages
 * @property string $name
 * @property integer $pathogen_type_id
 * @property string $bio_control
 * @property string $ch_control
 */
class Pathogen extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];
	public $timestamps = false;

    public $table = 'pathogens';




    public $fillable = [
        'name',
        'pathogen_type_id',
        'bio_control',
        'ch_control'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'pathogen_type_id' => 'integer',
        'bio_control' => 'string',
        'ch_control' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name.ar' => 'required|max:30',
        'name.en' => 'required|max:30',
        'pathogen_type_id' => 'required|exists:pathogen_types,id',
        'bio_control' => 'nullable|max:255',
        'ch_control' => 'nullable|max:255'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function diseases()
    {
        return $this->belongsToMany(\App\Models\Disease::class);
    }

    /**
     * Get the pathogenType that owns the Pathogen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pathogenType()
    {
        return $this->belongsTo(PathogenType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pathogenGrowthStages()
    {
        return $this->hasMany(\App\Models\PathogenGrowthStage::class);
    }
}
