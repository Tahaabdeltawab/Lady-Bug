<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcPaGrowthStage extends Model
{

    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['effect'];
    public $table = 'ac_pa_growth_stage';
	public $timestamps = false;

    public $fillable = [
        'ac_id',
        'pathogen_growth_stage_id',
        'effect',
    ];

    protected $casts = [
        'id' => 'integer',
        'ac_id' => 'integer',
        'effect' => 'string',
        'pathogen_growth_stage_id' => 'integer',
    ];

    public static $rules = [
        'ac_id' => 'required|exists:acs,id',
        'pathogen_growth_stage_id' => 'required|exists:pathogen_growth_stages,id',
        'effect.ar' => 'nullable|string|max:255',
        'effect.en' => 'nullable|string|max:255',
        'assets' => 'nullable|array',
        'assets.*' => 'nullable|max:5000|image',
    ];

    public function ac()
    {
        return $this->belongsTo(\App\Models\Ac::class);
    }

    public function pathogenGrowthStage()
    {
        return $this->belongsTo(\App\Models\PathogenGrowthStage::class);
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

}
