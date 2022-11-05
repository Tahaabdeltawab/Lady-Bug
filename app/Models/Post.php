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
        'type',
        'author_id',
        'farm_id',
        'business_id',
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
        'type' => 'string',
        'content' => 'string',
        'author_id' => 'integer',
        'farm_id' => 'integer',
        'business_id' => 'integer',
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
        'title' => 'nullable|max:30',
        'content' => 'nullable',
        'type' => 'nullable|in:story,article,post',
        'business_id' => 'nullable|exists:businesses,id',
        'farmed_type_id' => 'nullable',
        'post_type_id' => 'nullable|exists:post_types,id',
        'solved' => 'nullable',
        'shared_id' => 'nullable|exists:posts,id',
        'assets' => 'nullable|array',
        'assets.*' => 'nullable|max:20000|mimes:jpeg,jpg,png,svg,bmp,gif,webp,mp4,mov,wmv,qt,asf' //qt for mov , asf for wmv
    ];

    public function updateReactions(){
        $this->reactions_count = $this->comments()->count() + $this->likers()->count() + $this->dislikers()->count();
        $this->save();
    }
    protected static function booted(){
        static::addGlobalScope('latest', function($q){
             $q->latest();
        });
    }

    public function scopeVideo($query)
    {
        return $query->whereHas('assets', function ($q)
        {
            $q->whereIn('asset_mime', config('myconfig.video_mimes'));
        })->notStory();
    }

    public function scopePost($query)
    {
        return $query->notVideo()->notStory();
    }

    public function scopeNotVideo($query)
    {
        return $query->whereHas('assets', function ($q)
        {
            $q->whereNotIn('asset_mime', config('myconfig.video_mimes'));
        });
    }

    public function scopeBusiness($query)
    {
        return $query->where('post_type_id', 4);
    }

    // not article or story
    public function scopePostOrVideo($query)
    {
        return $query->where('type', 'post');
    }

    public function scopeArticle($query)
    {
        return $query->where('type', 'article');
    }

    public function scopeNotArticle($query)
    {
        return $query->where('type', '!=', 'article');
    }

    public function scopeStory($query)
    {
        return $query->where('type', 'story');
    }

    public function scopeNotStory($query)
    {
        return $query->where('type', '!=', 'story');
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
