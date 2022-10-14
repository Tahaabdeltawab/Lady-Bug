<?php

namespace App\Models;

use Carbon\Carbon;
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
	public $timestamps = false;




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
        'description' => 'nullable|max:255',
        'date' => 'nullable',
        'done' => 'nullable',
        'type' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function business()
    {
        return $this->belongsTo(\App\Models\Business::class);
    }

    public function getDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d');
    }
}
