<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="Information",
 *      required={"title", "content"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="content",
 *          description="content",
 *          type="string"
 *      )
 * )
 */
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Information extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    public $translatedAttributes = ['title', 'content'];


    public $table = 'information';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        // 'title',
        // 'content'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'title' => 'string',
        // 'content' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title_ar_localized' => 'required|max:200',
        'title_en_localized' => 'required|max:200',
        'content_ar_localized' => 'required',
        'content_en_localized' => 'required',
        // 'title' => 'required|max:200',
        // 'content' => 'required'
    ];

    
}
