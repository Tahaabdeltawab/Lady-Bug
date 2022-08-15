<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class PostType extends Model implements TranslatableContract
{
    use /*SoftDeletes,*/ Translatable;

    public $translatedAttributes = ['name'];

    public $table = 'post_types';


    protected $dates = ['deleted_at'];



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
        'name_ar_localized' => 'required|max:200',
        'name_en_localized' => 'required|max:200',
    ];


    // don't return the 'farm' post_type because it is not an actual post type, it just indicates the post in farmy
    protected static function booted()
    {
        static::addGlobalScope('all_except_farm', function ($builder) {
            $builder->where('id','!=' , 4);
        });
    }


}
