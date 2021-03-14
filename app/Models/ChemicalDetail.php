<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="ChemicalDetail",
 *      required={"type", "acidity", "acidity_value", "acidity_unit_id", "salt_type", "salt_concentration_value", "salt_concentration_unit_id", "salt_detail_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="acidity",
 *          description="acidity",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="acidity_value",
 *          description="acidity_value",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="acidity_unit_id",
 *          description="acidity_unit_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="salt_type",
 *          description="salt_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="salt_concentration_value",
 *          description="salt_concentration_value",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="salt_concentration_unit_id",
 *          description="salt_concentration_unit_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="salt_detail_id",
 *          description="salt_detail_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
class ChemicalDetail extends Model
{
    use SoftDeletes;


    public $table = 'chemical_details';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'type',
        'acidity_type_id',
        'acidity_value',
        'acidity_unit_id',
        'salt_type_id',
        'salt_concentration_value',
        'salt_concentration_unit_id',
        'salt_detail_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'acidity_type_id' => 'integer',
        'acidity_value' => 'double',
        'acidity_unit_id' => 'integer',
        'salt_type_id' => 'integer',
        'salt_concentration_value' => 'double',
        'salt_concentration_unit_id' => 'integer',
        'salt_detail_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'required',
        'acidity_type_id' => 'required',
        'acidity_value' => 'required',
        'acidity_unit_id' => 'required',
        'salt_type_id' => 'required',
        'salt_concentration_value' => 'required',
        'salt_concentration_unit_id' => 'required',
        'salt_detail_id' => 'required'
    ];

    public function salt_type()
    {
        return $this->belongsTo(SaltType::class);
    }
    
    public function salt_detail()
    {
        return $this->belongsTo(SaltDetail::class);
    }

    public function acidity_type()
    {
        return $this->belongsTo(AcidityType::class);
    }

    public function acidity_unit()
    {
        return $this->belongsTo(MeasuringUnit::class, 'acidity_unit_id');
    }
    
    public function salt_concentration_unit()
    {
        return $this->belongsTo(MeasuringUnit::class, 'salt_concentration_unit_id');
    }
    
}
