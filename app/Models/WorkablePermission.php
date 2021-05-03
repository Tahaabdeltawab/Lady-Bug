<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="WorkablePermission",
 *      required={""},
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
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="workable_type",
 *          description="workable_type",
 *          type="string"
 *      )
 * )
 */
class WorkablePermission extends Model
{
  // use SoftDeletes;


    public $table = 'workable_permissions';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'workable_type_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'workable_type_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


    public function workable_roles(){
        return $this->belongsToMany(WorkableRole::class, 'workable_permission_workable_role');
    }

    public function workable_type(){
        return $this->belongsTo(WorkableType::class, 'workable_type_id', 'id');
    }

}
