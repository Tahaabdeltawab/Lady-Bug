<?php

namespace App\Models;

use Eloquent as Model;



class FarmActivityType extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];
	public $timestamps = false;


    public $table = 'farm_activity_types';



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
        'name.ar' => 'required|max:30',
        'name.en' => 'required|max:30'
    ];


    // hide animals temporarily as commanded
    protected static function booted()
    {
        static::addGlobalScope('all_except_animal', function ($q) {
            $q->where('id', '!=', 4);
        });
    }

}
