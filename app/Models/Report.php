<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Report
 * @package App\Models
 * @version August 20, 2021, 1:27 am EET
 *
 * @property integer $report_type_id
 * @property string $reportable_type
 * @property integer $reportable_id
 * @property string $description
 * @property string $image
 * @property boolean $status
 */
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

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
