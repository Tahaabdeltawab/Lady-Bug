<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;



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

    public function scopeDone($q){
        return $q->where('done', true);
    }

    public function scopeOpen($q){
        return $q->where('done', false);
    }

}
