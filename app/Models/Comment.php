<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelLike\Traits\Likeable;


/**
 * @SWG\Definition(
 *      definition="Comment",
 *      required={"content", "commenter_id", "post_id"},
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
 *          property="content",
 *          description="content",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="commenter_id",
 *          description="commenter_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="parent_id",
 *          description="parent_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="post_id",
 *          description="post_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
class Comment extends Model
{
    use SoftDeletes, Likeable;


    public $table = 'comments';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'content',
        'commenter_id',
        'parent_id',
        'post_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'content' => 'string',
        'commenter_id' => 'integer',
        'parent_id' => 'integer',
        'post_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'content' => 'required',
        'post_id' => 'required|integer|exists:posts,id',
        'parent_id' => 'nullable|integer|exists:comments,id'
    ];

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // public function assets()
    // {
    //     return $this->belongsToMany(Asset::class, 'asset_post');
    // }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function commenter()
    {
        return $this->belongsTo(User::class);
    }

    
}
