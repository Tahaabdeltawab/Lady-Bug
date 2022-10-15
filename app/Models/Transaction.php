<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Transaction
 * @package App\Models
 * @version September 9, 2022, 5:33 pm EET
 *
 * @property \App\Models\User $user
 * @property string $type
 * @property integer $user_id
 * @property string $gateway
 * @property number $total
 * @property string $description
 */
class Transaction extends Model
{


    public $table = 'transactions';




    public $fillable = [
        'type',
        'user_id',
        'gateway',
        'total',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'user_id' => 'integer',
        'gateway' => 'string',
        'total' => 'decimal:2',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'required',
        'gateway' => 'nullable',
        'total' => 'required',
        'description' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
