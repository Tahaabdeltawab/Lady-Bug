<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class FarmedTypeFertilizationNeed
 * @package App\Models
 * @version September 10, 2022, 2:09 pm EET
 *
 * @property \App\Models\FarmedType $farmedType
 * @property \App\Models\NutElemValue $nutElemValue
 * @property integer $farmed_type_id
 * @property string $stage
 * @property string $per
 * @property integer $nut_elem_value_id
 */
class FarmedTypeFertilizationNeed extends Model
{


    public $table = 'farmed_type_fertilization_needs';
	public $timestamps = false;




    public $fillable = [
        'farmed_type_id',
        'farmed_type_stage_id',
        // 'per',
        'nut_elem_value_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'farmed_type_id' => 'integer',
        'farmed_type_stage_id' => 'integer',
        // 'per' => 'string',
        'nut_elem_value_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'farmed_type_id' => 'required|exists:farmed_types,id',
        'farmed_type_stage_id' => 'nullable|exists:farmed_type_stages,id',
        // 'per' => 'nullable|in:acre,tree',
        // 'nut_elem_value_id' => 'required'
        'nut_elem_value.n' => 'required|numeric|min:0|max:100',
        'nut_elem_value.p' => 'required|numeric|min:0|max:100',
        'nut_elem_value.k' => 'required|numeric|min:0|max:100',
        'nut_elem_value.fe' => 'required|numeric|min:0|max:100',
        'nut_elem_value.b' => 'required|numeric|min:0|max:100',
        'nut_elem_value.ca' => 'required|numeric|min:0|max:100',
        'nut_elem_value.mg' => 'required|numeric|min:0|max:100',
        'nut_elem_value.s' => 'required|numeric|min:0|max:100',
        'nut_elem_value.zn' => 'required|numeric|min:0|max:100',
        'nut_elem_value.mn' => 'required|numeric|min:0|max:100',
        'nut_elem_value.cu' => 'required|numeric|min:0|max:100',
        'nut_elem_value.cl' => 'required|numeric|min:0|max:100',
        'nut_elem_value.mo' => 'required|numeric|min:0|max:100',
    ];

    protected $appends = ['per'];

    /**
     * لو الصنف محصول يبقى معدل التسميد لكل فدان
     *  ولو اشجار يبقى لكل شجرة
     */
    public function getPerAttribute()
    {
        return $this->farmedType->farm_activity_type_id == 1 ? 'acre' : 'tree';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmedType()
    {
        return $this->belongsTo(\App\Models\FarmedType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmedTypeStage()
    {
        return $this->belongsTo(\App\Models\FarmedTypeStage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function nutElemValue()
    {
        return $this->belongsTo(\App\Models\NutElemValue::class);
    }
}
