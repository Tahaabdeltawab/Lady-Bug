<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Task
 * @package App\Models
 * @version September 16, 2022, 2:43 pm EET
 *
 * @property \App\Models\FarmReport $farmReport
 * @property \App\Models\Farm $farm
 * @property \App\Models\Business $business
 * @property \App\Models\TaskType $taskType
 * @property \App\Models\Insecticide $insecticide
 * @property \App\Models\Fertilizer $fertilizer
 * @property integer $farm_report_id
 * @property integer $farm_id
 * @property integer $business_id
 * @property string $date
 * @property integer $week
 * @property integer $task_type_id
 * @property integer $insecticide_id
 * @property integer $fertilizer_id
 * @property number $quantity
 * @property string $quantity_unit
 * @property boolean $done
 */
class Task extends Model
{


    public $table = 'tasks';
	public $timestamps = false;
    



    public $fillable = [
        'farm_report_id',
        'farm_id',
        'business_id',
        'date',
        'week',
        'task_type_id',
        'insecticide_id',
        'fertilizer_id',
        'quantity',
        'quantity_unit',
        'notes',
        'done'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'farm_report_id' => 'integer',
        'farm_id' => 'integer',
        'business_id' => 'integer',
        'date' => 'date',
        'week' => 'integer',
        'task_type_id' => 'integer',
        'insecticide_id' => 'integer',
        'fertilizer_id' => 'integer',
        'quantity' => 'decimal:2',
        'quantity_unit' => 'string',
        'notes' => 'string',
        'done' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'farm_report_id' => 'nullable|exists:farm_reports,id',
        'farm_id' => 'nullable|exists:farms,id',
        'business_id' => 'nullable|exists:businesses,id',
        'task_type_id' => 'required|exists:task_types,id',
        'insecticide_id' => 'nullable',
        'fertilizer_id' => 'nullable',
        'date' => 'nullable|date_format:Y-m-d',
        'week' => 'nullable|in:1,2,3,4',
        'quantity' => 'nullable',
        'quantity_unit' => 'nullable',
        'notes' => 'nullable',
        'done' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farm_report()
    {
        return $this->belongsTo(\App\Models\FarmReport::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farm()
    {
        return $this->belongsTo(\App\Models\Farm::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function business()
    {
        return $this->belongsTo(\App\Models\Business::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function task_type()
    {
        return $this->belongsTo(\App\Models\TaskType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insecticide()
    {
        return $this->belongsTo(\App\Models\Insecticide::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fertilizer()
    {
        return $this->belongsTo(\App\Models\Fertilizer::class);
    }
}
