<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class FarmReport
 * @package App\Models
 * @version September 13, 2022, 7:32 pm EET
 *
 * @property \App\Models\Farm $farm
 * @property \App\Models\FarmedTypeStage $farmedTypeStage
 * @property integer $farm_id
 * @property integer $farmed_type_stage_id
 * @property string $fertilization_start_date
 * @property string $fertilization_unit
 * @property string $notes
 */
class FarmReport extends Model
{


    public $table = 'farm_reports';




    public $fillable = [
        'business_id',
        'user_id',
        'farm_id',
        'farmed_type_stage_id',
        'location_id',
        // 'fertilization_start_date',
        // 'fertilization_unit',
        'notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'business_id' => 'integer',
        'location_id' => 'integer',
        'user_id' => 'integer',
        'farm_id' => 'integer',
        'farmed_type_stage_id' => 'integer',
        'fertilization_start_date' => 'date',
        // 'fertilization_unit' => 'string',
        'notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'farm_id' => 'required|exists:farms,id',
        'business_id' => 'required|exists:businesses,id',
        'farmed_type_stage_id' => 'nullable|exists:farmed_type_stages,id',
        'location' => 'array',
        'location.latitude' => 'required',
        'location.longitude' => 'required',
        'location.country' => 'nullable',
        'location.city' => 'nullable',
        'location.district' => 'nullable',
        'location.details' => 'nullable',
        'location.postal' => 'nullable',
        'fertilization_start_date' => 'nullable|date_format:Y-m-d',
        // 'fertilization_unit' => 'nullable|in:tree,acre',
        'notes' => 'nullable'
    ];

    // GETTERS
    public function getFertilizationStartDateAttribute($value){
        return @$this->farm->fertilization_start_date;
    }
    /**
     * لو الصنف محصول يبقى معدل التسميد لكل فدان
     *  ولو اشجار يبقى لكل شجرة
     */
    public function getFertilizationUnitAttribute()
    {
        return $this->farm->farmed_type->farm_activity_type_id == 1 ? 'acre' : 'tree';
    }



    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function farm()
    {
        return $this->belongsTo(\App\Models\Farm::class);
    }

    public function business()
    {
        return $this->belongsTo(\App\Models\Business::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmed_type_stage()
    {
        return $this->belongsTo(\App\Models\FarmedTypeStage::class);
    }


    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class);
    }
}
