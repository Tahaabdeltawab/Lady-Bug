<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Education
 * @package App\Models
 * @version September 9, 2022, 6:11 pm EET
 *
 * @property \App\Models\User $user
 * @property integer $user_id
 * @property string $title
 * @property string $date
 */
class Education extends Model
{


    public $table = 'education';
    



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