<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Visited
 * @package App\Models
 * @version September 9, 2022, 6:17 pm EET
 *
 * @property \App\Models\User $user
 * @property integer $user_id
 * @property string $title
 * @property string $date
 */
class Visited extends Model
{


    public $table = 'visiteds';
	public $timestamps = false;
    



    public $fillable = [
        'user_id',
        'title',
        'date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'title' => 'string',
        'date' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'date' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
