<?php

namespace App\Models;

use Eloquent as Model;

class Report extends Model
{


    public $table = 'reports';


    public $fillable = [
        'report_type_id',
        'reportable_type',
        'reportable_id',
        'description',
        'status',
        'reporter_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'report_type_id' => 'integer',
        'reportable_type' => 'string',
        'reportable_id' => 'integer',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'report_type_id' => 'required|exists:report_types,id',
        'description' => 'nullable|string',
        'status' => 'required'
    ];

    public function reportable()
    {
        return $this->morphTo();
    }

 
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
