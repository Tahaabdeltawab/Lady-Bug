<?php

namespace App\Models;

use Eloquent as Model;

class NotificationSetting extends Model
{


    public $table = 'notification_settings';
	public $timestamps = false;




    public $fillable = [
        'user_id',
        'timeline_interactions',
        'followings_posts',
        'tasks',
        'my_business_interactions',
        'products',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'timeline_interactions' => 'nullable|boolean',
        'followings_posts' => 'nullable|boolean',
        'tasks' => 'nullable|boolean',
        'my_business_interactions' => 'nullable|boolean',
        'products' => 'nullable|boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
