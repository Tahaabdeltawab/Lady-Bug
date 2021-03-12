<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="Post",
 *      required={"title", "content", "author_id", "farm_id", "farmed_type_id", "post_type_id", "solved"},
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
 *      ),
 *      @SWG\Property(
 *          property="author_id",
 *          description="author_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="farm_id",
 *          description="farm_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="farmed_type_id",
 *          description="farmed_type_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="post_type_id",
 *          description="post_type_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="solved",
 *          description="solved",
 *          type="boolean"
 *      )
 * )
 */
class Post extends Model
{
    use SoftDeletes;


    public $table = 'posts';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'title',
        'content',
        'author_id',
        'farm_id',
        'farmed_type_id',
        'post_type_id',
        'solved'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'content' => 'string',
        'author_id' => 'integer',
        'farm_id' => 'integer',
        'farmed_type_id' => 'integer',
        'post_type_id' => 'integer',
        'solved' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:200',
        'content' => 'required',
        'author_id' => 'required',
        'farm_id' => 'required',
        'farmed_type_id' => 'required',
        'post_type_id' => 'required',
        'solved' => 'required'
    ];

    
}
