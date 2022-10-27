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
        'name',
        'pathogen_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'pathogen_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name.ar' => 'required|max:30',
        'name.en' => 'required|max:30',
        'pathogen_id' => 'required|exists:pathogens,id',
        'assets' => 'nullable|array',
        'assets.*' => 'nullable|max:5000|image',

    ];


    public function pathogen()
    {
        return $this->belongsTo(Pathogen::class);
    }

    public function affectingAcs()
    {
        return $this->belongsToMany(Ac::class, 'ac_pa_growth_stage', 'pathogen_growth_stage_id', 'ac_id')->withPivot('effect');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
