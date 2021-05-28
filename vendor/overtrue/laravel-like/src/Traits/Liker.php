<?php

namespace Overtrue\LaravelLike\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Overtrue\LaravelLike\Like;

trait Liker
{
    /**
     * @param  \Illuminate\Database\Eloquent\Model  $object
     *
     * @return Like
     */
    public function like(Model $object): Like
    {
        $attributes = [
            'likeable_type' => $object->getMorphClass(),
            'likeable_id' => $object->getKey(),
            config('like.user_foreign_key') => $this->getKey(),
            'is_like' => 1,
        ];

        /* @var \Illuminate\Database\Eloquent\Model $like */
        $like = \app(config('like.like_model'));

        /* @var \Overtrue\LaravelLike\Traits\Likeable|\Illuminate\Database\Eloquent\Model $object */
        return $like->where($attributes)->firstOr(
            function () use ($like, $attributes, $object) {
                $like->unguard();

                if ($this->relationLoaded('likes')) {
                    $this->unsetRelation('likes');
                }

                if($this->hasDisliked($object))
                {
                    $this->undislike($object);
                }

                return $like->create($attributes);
            }
        );
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $object
     *
     * @return bool
     * @throws \Exception
     */
    public function unlike(Model $object): bool
    {
        /* @var \Overtrue\LaravelLike\Like $relation */
        $relation = \app(config('like.like_model'))
            ->where('likeable_id', $object->getKey())
            ->where('likeable_type', $object->getMorphClass())
            ->where(config('like.user_foreign_key'), $this->getKey())
            ->where('is_like', 1)
            ->first();

        if ($relation) {
            if ($this->relationLoaded('likes')) {
                $this->unsetRelation('likes');
            }

            return $relation->delete();
        }

        return true;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $object
     *
     * @return Like|null
     * @throws \Exception
     */
    public function toggleLike(Model $object)
    {
        if($this->hasLiked($object))
        {
            $this->unlike($object);
            $msg = 'Like removed successfully';
        }
        else
        {
            $this->like($object);
            $msg = 'Liked successfully';
        }
        return $msg;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $object
     *
     * @return bool
     */
    public function hasLiked(Model $object): bool
    {
        return ($this->relationLoaded('likes') ? $this->likes : $this->likes())
                ->where('likeable_id', $object->getKey())
                ->where('likeable_type', $object->getMorphClass())
                ->count() > 0;
    }

    public function likes(): HasMany
    {
        return $this->hasMany(config('like.like_model'), config('like.user_foreign_key'), $this->getKeyName())
        ->where('is_like', 1);
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(config('like.like_model'), config('like.user_foreign_key'), $this->getKeyName())
        ->where('is_like', 0);
    }



///////////////////////////////////////////////////////////////////
        //DISLIKES (THE OPPOSITE OF LIKES) {BY ME: TAHA}//

    public function dislike(Model $object): Like
    {
        $attributes = [
            'likeable_type' => $object->getMorphClass(),
            'likeable_id' => $object->getKey(),
            config('like.user_foreign_key') => $this->getKey(), //liker_id
            'is_like' => 0,
        ];

        /* @var \Illuminate\Database\Eloquent\Model $like */
        $like = \app(config('like.like_model'));

        /* @var \Overtrue\LaravelLike\Traits\Likeable|\Illuminate\Database\Eloquent\Model $object */
        return $like->where($attributes)->firstOr(
            function () use ($like, $attributes, $object) {
                $like->unguard();

                if ($this->relationLoaded('likes')) {
                    $this->unsetRelation('likes');
                }

                if($this->hasLiked($object))
                {
                    $this->unlike($object);
                }

                return $like->create($attributes);
            }
        );
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $object
     *
     * @return bool
     * @throws \Exception
     */
    public function undislike(Model $object): bool
    {
        /* @var \Overtrue\LaravelLike\Like $relation */
        $relation = \app(config('like.like_model'))
            ->where('likeable_id', $object->getKey())
            ->where('likeable_type', $object->getMorphClass())
            ->where(config('like.user_foreign_key'), $this->getKey())
            ->where('is_like', 0)
            ->first();

        if ($relation) {
            if ($this->relationLoaded('likes')) {
                $this->unsetRelation('likes');
            }

            return $relation->delete();
        }

        return true;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $object
     *
     * @return Like|null
     * @throws \Exception
     */
    public function toggleDislike(Model $object)
    {
        if($this->hasDisliked($object))
        {
            $this->undislike($object);
            $msg = 'Dislike removed successfully';
        }
        else
        {
            $this->dislike($object);
            $msg = 'Disliked successfully';
        }
        return $msg;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $object
     *
     * @return bool
     */
    public function hasDisliked(Model $object): bool
    {
        return ($this->relationLoaded('likes') ? $this->dislikes : $this->dislikes())
                ->where('likeable_id', $object->getKey())
                ->where('likeable_type', $object->getMorphClass())
                ->count() > 0;
    }
}
