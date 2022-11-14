<?php

namespace App\Traits;

use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait Rateable
{
    /**
     * This model has many ratings.
     *
     * @param mixed $rating
     * @param mixed $value
     *
     * @return Rating
     */
    public function rate($value, $questions = [])
    {
        $rating = new Rating();
        $rating->rating = $value;
        $rating->user_id = Auth::id();

        $this->ratings()->save($rating);
        foreach ($questions as $id => $answer) {
            DB::table('rating_rating_question')->insert([
                'rating_id' => $rating->id,
                'rating_question_id' => $id,
                'rateable_id' => $rating->rateable_id,
                'answer' => $answer,
            ]);
        }
        return $rating;
    }

    public function rateOnce($value, $questions = [])
    {
        $rating = Rating::query()
            ->where('rateable_type', '=', get_class($this))
            ->where('rateable_id', '=', $this->id)
            ->where('user_id', '=', Auth::id())
            ->first()
        ;

        if ($rating) {
            $rating->rating = $value;
            $rating->save();
            DB::table('rating_rating_question')->where('rating_id', $rating->id)->delete();
            foreach ($questions as $id => $answer) {
                DB::table('rating_rating_question')->insert([
                    'rating_id' => $rating->id,
                    'rating_question_id' => $id,
                    'rateable_id' => $rating->rateable_id,
                    'answer' => $answer,
                ]);
            }
            return $rating;
        } else {
            return $this->rate($value, $questions);
        }
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function sumRating()
    {
        return $this->ratings()->sum('rating');
    }

    public function timesRated()
    {
        return $this->ratings()->count();
    }

    public function usersRated()
    {
        return $this->ratings()->groupBy('user_id')->pluck('user_id')->count();
    }

    public function isRatedBy($id)
    {
        return $this->ratings()->groupBy('user_id')->pluck('user_id')->contains($id);
    }

    public function userAverageRating()
    {
        return $this->ratings()->where('user_id', Auth::id())->avg('rating');
    }

    public function userSumRating()
    {
        return $this->ratings()->where('user_id', Auth::id())->sum('rating');
    }

    public function ratingPercent($max = 5)
    {
        $quantity = $this->ratings()->count();
        $total = $this->sumRating();

        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }

    // Getters

    public function getAverageRatingAttribute()
    {
        return $this->averageRating();
    }

    public function getFormattedAverageRatingAttribute()
    {
        return $this->averageRating() ? number_format($this->averageRating(), 1, '.' , '') : null;

    }

    public function getSumRatingAttribute()
    {
        return $this->sumRating();
    }

    public function getUserAverageRatingAttribute()
    {
        return $this->userAverageRating();
    }

    public function getUserSumRatingAttribute()
    {
        return $this->userSumRating();
    }
}
