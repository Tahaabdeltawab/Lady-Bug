<?php

namespace App\Models;

use Eloquent as Model;


class WorkField extends Model
{
    use \App\Traits\SpatieHasTranslations;

    public $table = 'work_fields';
    public $translatable = ['name'];
	public $timestamps = false;    



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
        'name' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_ar_localized' => 'required|max:200',
        'name_en_localized' => 'required|max:200',
    ];

    
}