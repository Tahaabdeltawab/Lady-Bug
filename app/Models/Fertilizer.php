<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Fertilizer
 * @package App\Models
 * @version September 11, 2022, 6:59 pm EET
 *
 * @property \App\Models\NutElemValue $nutElemValue
 * @property \App\Models\Country $country
 * @property \App\Models\Product $product
 * @property string $name
 * @property integer $nut_elem_value_id
 * @property string $dosage_form
 * @property string $producer
 * @property integer $country_id
 * @property string $addition_way
 * @property string $conc
 * @property string $reg_date
 * @property string $reg_num
 * @property number $mix_ph
 * @property string $usage_rate
 * @property integer $expiry
 * @property string $precautions
 * @property string $notes
 */
class Fertilizer extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name', 'notes', 'precautions'];
	public $timestamps = false;

    public $table = 'fertilizers';
    



    public $fillable = [
        'name',
        'nut_elem_value_id',
        'dosage_form',
        'producer',
        'country_id',
        'addition_way',
        'conc',
        'reg_date',
        'reg_num',
        'mix_ph',
        'usage_rate',
        'expiry',
        'precautions',
        'notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'nut_elem_value_id' => 'integer',
        'dosage_form' => 'string',
        'producer' => 'string',
        'country_id' => 'integer',
        'addition_way' => 'string',
        'conc' => 'string',
        'reg_date' => 'date',
        'reg_num' => 'string',
        'mix_ph' => 'decimal:2',
        'usage_rate' => 'string',
        'expiry' => 'integer',
        'precautions' => 'string',
        'notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'nut_elem_value' => 'nullable',
        'dosage_form' => 'nullable|in:powder,liquid',
        'producer' => 'nullable',
        'country_id' => 'nullable',
        'addition_way' => 'nullable',
        'conc' => 'nullable',
        'reg_date' => 'nullable',
        'reg_num' => 'nullable',
        'mix_ph' => 'nullable',
        'usage_rate' => 'nullable',
        'expiry' => 'nullable',
        'precautions' => 'nullable',
        'notes' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function nutElemValue()
    {
        return $this->belongsTo(\App\Models\NutElemValue::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function product()
    {
        return $this->hasOne(\App\Models\Product::class);
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
