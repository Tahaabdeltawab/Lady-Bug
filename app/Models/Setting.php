<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Setting
 * @package App\Models
 * @version September 25, 2022, 11:31 am EET
 *
 * @property string $name
 * @property string $value
 * @property string $type
 */
class Setting extends Model
{


    public $table = 'settings';
	public $timestamps = false;




    public $fillable = [
        'name',
        'value',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'value' => 'string',
        'type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|in:report_price,weather_background',
        'value' => 'requiredIf:asset,null',
        'type' => 'nullable',
        'asset' => 'nullable|max:5000|image',
    ];

    public function asset()
    {
        return $this->morphOne(Asset::class, 'assetable');
    }


}
