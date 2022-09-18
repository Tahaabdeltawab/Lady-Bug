<?php

namespace App\Models;

use Eloquent as Model;
use Overtrue\LaravelLike\Traits\Likeable;

class Post extends Model
{
    use Likeable;


    public $table = 'posts';



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

    public function updateReactions(){
        $this->reactions_count = $this->comments->count() + $this->likers->count() + $this->dislikers->count();
        $this->save();
    }
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

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function farmed_type()
    {
        return $this->belongsTo(FarmedType::class);
    }

    public function post_type()
    {
        return $this->belongsTo(PostType::class);
    }

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
