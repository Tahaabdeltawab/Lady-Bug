<?php

namespace App\Models;

use Eloquent as Model;



class ServiceTable extends Model
{

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
