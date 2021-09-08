<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="ServiceTable",
 *      required={"name", "farm_id"},
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
 *          property="farm_id",
 *          description="farm_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */

class ServiceTable extends Model
{
    // use SoftDeletes;

    public $table = 'service_tables';


    protected $dates = [
        'deleted_at',
        'start_at',
        'notify_at',
        'due_at',
    ];



    public $fillable = [
        'name',
        'farm_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'farm_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:200',
        'farm_id' => 'required|exists:farms,id'
    ];

    public function tasks(){
        return $this->hasMany(ServiceTask::class);
    }

    public function farm(){
        return $this->belongsTo(Farm::class);
    }

}
