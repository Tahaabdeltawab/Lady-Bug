<?php

namespace App\Models;

use Eloquent as Model;



class TaskType extends Model
{
    use \App\Traits\SpatieHasTranslations;

    public $table = 'task_types';

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
        'id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name.ar' => 'required|max:30',
        'name.en' => 'required|max:30',
    ];


}
