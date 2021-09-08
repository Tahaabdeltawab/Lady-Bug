<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelLike\Traits\Likeable;

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
    use /*SoftDeletes,*/ Likeable;


    public $table = 'posts';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'title',
        'content',
        'author_id',
        'farm_id',
        'farmed_type_id',
        'post_type_id',
        'solved',
        'shared_id',
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
        'solved' => 'boolean',
        'shared_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'nullable|max:200',
        'content' => 'required',
        'author_id' => 'nullable',
        'farm_id' => 'nullable',
        'farmed_type_id' => 'nullable',
        'post_type_id' => 'required',
        'solved' => 'nullable',
        'asset' => 'nullable'
    ];

    protected static function booted(){
        static::addGlobalScope('latest', function($q){
             $q->latest();
        });
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeReported($query)
    {
        return $query->where('status', 'reported');
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    public function user_id(){
        return $this->author_id;
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function farmed_type()
    {
        return $this->belongsTo(FarmedType::class);
    }

    public function post_type()
    {
        return $this->belongsTo(PostType::class);
    }

    // public function assets()
    // {
    //     return $this->belongsToMany(Asset::class, 'asset_post');
    // }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shared()
    {
        return $this->belongsTo(Post::class, 'shared_id', 'id');
    }


}
