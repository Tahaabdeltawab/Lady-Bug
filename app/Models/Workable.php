<?php

namespace App\Models;

// use Eloquent as Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @SWG\Definition(
 *      definition="Workable",
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
 *          property="worker_id",
 *          description="worker_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="workable_id",
 *          description="workable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="workable_type",
 *          description="workable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="boolean"
 *      )
 * )
 */
class Workable extends MorphPivot
{
    // use SoftDeletes;


    public $table = 'workables';

    public $incrementing  = true;


    protected $dates = ['deleted_at'];



    public $fillable = [
        'worker_id',
        'workable_id',
        'workable_type',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'worker_id' => 'integer',
        'workable_id' => 'integer',
        'workable_type' => 'string',
        'status' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function workable_roles(){
        return $this->belongsToMany(WorkableRole::class, 'workable_workable_role', 'workable_id', 'workable_role_id')->withPivot('status')->withTimestamps();
    }


}
