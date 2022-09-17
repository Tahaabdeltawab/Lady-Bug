<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class BusinessPart
 * @package App\Models
 * @version September 14, 2022, 2:26 pm EET
 *
 * @property \App\Models\Business $business
 * @property integer $business_id
 * @property string $title
 * @property string $description
 * @property string $date
 * @property boolean $done
 * @property string $type
 */
class BusinessPart extends Model
{


    public $table = 'business_parts';
    



    public $fillable = [
        'business_id',
        'title',
        'description',
        'date',
        'done',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'business_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'date' => 'date',
        'done' => 'boolean',
        'type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'business_id' => 'required',
        'title' => 'required',
        'description' => 'nullable,max:255',
        'date' => 'nullable',
        'done' => 'nullable',
        'type' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function business()
    {
        return $this->belongsTo(\App\Models\Business::class);
    }
}