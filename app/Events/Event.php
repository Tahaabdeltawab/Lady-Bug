<?php

namespace App\Events;

use App\Models\Followable;

class Event
{
    public $followable_id;
    public $followable_type;
    public $follower_id;
    public $user_id;

    protected Followable $relation;

    public function __construct(Followable $follower)
    {
        $this->follower_id = $this->user_id = $follower->{config('follow.user_foreign_key', 'user_id')};
        $this->followable_id = $follower->followable_id;
        $this->followable_type = $follower->followable_type;
    }
}
