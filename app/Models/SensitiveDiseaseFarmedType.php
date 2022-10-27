<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensitiveDiseaseFarmedType extends Model
{

    public $table = 'sensitive_disease_farmed_type';
	public $timestamps = false;

    public $fillable = [
        'disease_id',
        'farmed_type_id',
        'farmed_type_stage_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'disease_id' => 'integer',
        'farmed_type_id' => 'integer',
        'farmed_type_stage_id' => 'integer',
    ];

    public static $rules = [
        'disease_id' => 'required|exists:diseases,id',
        'farmed_type_id' => 'required|exists:farmed_types,id',
        'farmed_type_stage_id' => 'nullable|exists:farmed_type_stages,id',
        'assets' => 'nullable|array',
        'assets.*' => 'nullable|max:5000|image',
    ];

    public function farmedType()
    {
        return $this->belongsTo(\App\Models\FarmedType::class);
    }

    public function farmedTypeStage()
    {
        return $this->belongsTo(\App\Models\FarmedTypeStage::class);
    }

    public function disease()
    {
        return $this->belongsTo(\App\Models\Disease::class);
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

}
