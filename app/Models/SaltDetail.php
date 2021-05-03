<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="SaltDetail",
 *      required={"type", "PH", "CO3", "HCO3", "Cl", "SO4", "Ca", "Mg", "K", "Na", "Na2CO3"},
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
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="PH",
 *          description="PH",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="CO3",
 *          description="CO3",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="HCO3",
 *          description="HCO3",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="Cl",
 *          description="Cl",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="SO4",
 *          description="SO4",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="Ca",
 *          description="Ca",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="Mg",
 *          description="Mg",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="K",
 *          description="K",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="Na",
 *          description="Na",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="Na2CO3",
 *          description="Na2CO3",
 *          type="number",
 *          format="number"
 *      )
 * )
 */
class SaltDetail extends Model
{
  // use SoftDeletes;


    public $table = 'salt_details';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'saltable_type',
        'PH',
        'CO3',
        'HCO3',
        'Cl',
        'SO4',
        'Ca',
        'Mg',
        'K',
        'Na',
        'Na2CO3'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'saltable_type' => 'string',
        'PH' => 'double',
        'CO3' => 'double',
        'HCO3' => 'double',
        'Cl' => 'double',
        'SO4' => 'double',
        'Ca' => 'double',
        'Mg' => 'double',
        'K' => 'double',
        'Na' => 'double',
        'Na2CO3' => 'double'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'saltable_type' => 'required',
        'PH' => 'required',
        'CO3' => 'required',
        'HCO3' => 'required',
        'Cl' => 'required',
        'SO4' => 'required',
        'Ca' => 'required',
        'Mg' => 'required',
        'K' => 'required',
        'Na' => 'required',
        'Na2CO3' => 'required'
    ];


}
