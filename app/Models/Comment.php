<?php

namespace App\Models;

use Eloquent as Model;
use Overtrue\LaravelLike\Traits\Likeable;


class Comment extends Model
{
    use Likeable;


    public $table = 'comments';



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

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function siblings()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id')
        ->where('comments.commenter_id', '!=', $this->commenter_id)
        ->groupBy('comments.commenter_id');
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

    public function user_id(){
        return $this->commenter_id;
    }

}
