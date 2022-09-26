<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class MarketingData
 * @package App\Models
 * @version September 11, 2022, 4:05 pm EET
 *
 * @property \App\Models\FarmedType $farmedType
 * @property \App\Models\Country $country
 * @property integer $farmed_type_id
 * @property string $year
 * @property integer $country_id
 * @property number $production
 * @property number $consumption
 * @property number $export
 * @property number $price_avg
 */
class MarketingData extends Model
{


    public $table = 'marketing_datas';
	public $timestamps = false;
    



    public $fillable = [
        'farmed_type_id',
        'year',
        'country_id',
        'production',
        'consumption',
        'export',
        'price_avg'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'farmed_type_id' => 'integer',
        'year' => 'string',
        'country_id' => 'integer',
        'production' => 'decimal:2',
        'consumption' => 'decimal:2',
        'export' => 'decimal:2',
        'price_avg' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'farmed_type_id' => 'required',
        'year' => 'nullable',
        'country_id' => 'nullable',
        'production' => 'nullable',
        'consumption' => 'nullable',
        'export' => 'nullable',
        'price_avg' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmedType()
    {
        return $this->belongsTo(\App\Models\FarmedType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }
}
