<?php

namespace App\Models;

use Eloquent as Model;

class ReportType extends Model
{
    use \App\Traits\SpatieHasTranslations;

    public $translatable = ['name'];
	public $timestamps = false;

    public $table = 'report_types';

    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
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
