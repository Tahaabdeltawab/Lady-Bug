<?php

namespace App\Models;

use Eloquent as Model;



class FarmingWay extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];
	public $timestamps = false;


    public $table = 'farming_ways';



    public $fillable = [
        'name',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    // public static $rules = [
    //     'name' => 'required|max:200',
    //     'type' => 'required|in:farming,breeding'
    // ];

}
