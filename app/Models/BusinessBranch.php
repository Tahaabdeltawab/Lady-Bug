<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class BusinessBranch
 * @package App\Models
 * @version September 10, 2022, 5:48 pm EET
 *
 * @property \App\Models\Business $business
 * @property string $name
 * @property string $address
 */
class BusinessBranch extends Model
{


    public $table = 'business_branches';
	public $timestamps = false;
    



    public $fillable = [
        'business_id',
        'name',
        'address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'business_id' => 'integer',
        'name' => 'string',
        'address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'business_id' => 'required|exists:businesses,id',
        'name' => 'required',
        'address' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function business()
    {
        return $this->belongsTo(\App\Models\Business::class);
    }
}
