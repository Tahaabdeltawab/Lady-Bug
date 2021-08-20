<?php

namespace App\Models;

use Eloquent as Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


/**
 * Class ReportType
 * @package App\Models
 * @version August 20, 2021, 1:21 am EET
 *
 */
class ReportType extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];

    public $table = 'report_types';

    public $fillable = [
        // 'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'name' => 'required|string'
    ];


}