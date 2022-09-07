<?php

namespace App\Models;

use Eloquent as Model;



class FarmedTypeGinfo extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['title', 'content'];


    public $table = 'farmed_type_ginfos';



    public $fillable = [
        'title',
        'content',
        'farmed_type_id',
        'farmed_type_stage_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'content' => 'string',
        'farmed_type_id' => 'integer',
        'farmed_type_stage_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }


    public function farmed_type_stage()
    {
        return $this->belongsTo(FarmedTypeStage::class);
    }


    public function farmed_type()
    {
        return $this->belongsTo(FarmedType::class);
    }

}
