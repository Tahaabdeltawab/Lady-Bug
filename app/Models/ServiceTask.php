<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="ServiceTask",
 *      required={"name", "start_at", "notify_at", "farm_id", "service_table_id", "type", "quantity", "quantity_unit_id", "due_at", "done"},
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
 *          property="start_at",
 *          description="start_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="notify_at",
 *          description="notify_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="farm_id",
 *          description="farm_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="service_table_id",
 *          description="service_table_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="quantity",
 *          description="quantity",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="quantity_unit_id",
 *          description="quantity_unit_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="due_at",
 *          description="due_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="done",
 *          description="done",
 *          type="boolean"
 *      )
 * )
 */

class ServiceTask extends Model
{
    // use SoftDeletes;

    public $table = 'service_tasks';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'start_at',
        'notify_at',
        'farm_id',
        'service_table_id',
        'task_type_id',
        'quantity',
        'quantity_unit_id',
        'due_at',
        'done'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'farm_id' => 'integer',
        'service_table_id' => 'integer',
        'task_type_id' => 'integer',
        'quantity' => 'integer',
        'quantity_unit_id' => 'integer',
        'done' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function task_type()
    {
        return $this->belongsTo(TaskType::class);
    }

    public function farm(){
        return $this->belongsTo(Farm::class);
    }

}
