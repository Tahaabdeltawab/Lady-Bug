<?php

namespace App\Models;

use Eloquent as Model;



class PostType extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];
	public $timestamps = false;

    public $table = 'post_types';



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
        'name.en' => 'required|max:30',
    ];


    // don't return the 'farm' post_type because it is not an actual post type, it just indicates the post in a farm
    protected static function booted()
    {
        static::addGlobalScope('all_except_farm', function ($builder) {
            $builder->where('id','!=' , 4);
        });
    }


}
