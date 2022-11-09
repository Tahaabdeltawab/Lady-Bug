<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Insecticide
 * @package App\Models
 * @version September 11, 2022, 6:53 pm EET
 *
 * @property \App\Models\Product $product
 * @property \Illuminate\Database\Eloquent\Collection $acs
 * @property string $name
 * @property string $dosage_form
 * @property string $producer
 * @property integer $country_id
 * @property string $conc
 * @property string $reg_date
 * @property string $reg_num
 * @property number $mix_ph
 * @property number $mix_rate
 * @property integer $expiry
 * @property string $precautions
 * @property string $notes
 */
class Insecticide extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name', 'notes', 'precautions'];
	public $timestamps = false;

    public $table = 'insecticides';




    public $fillable = [
        'name',
        'dosage_form',
        'producer',
        'country_id',
        'conc',
        'reg_date',
        'reg_num',
        'mix_ph',
        'mix_rate',
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
        'dosage_form' => 'string',
        'producer' => 'string',
        'country_id' => 'integer',
        'conc' => 'string',
        'reg_date' => 'date',
        'reg_num' => 'string',
        'mix_ph' => 'decimal:2',
        'mix_rate' => 'decimal:2',
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
        'name.ar' => 'required|max:30',
        'name.en' => 'required|max:30',
        'dosage_form' => 'nullable|in:powder,liquid',
        'acs' => 'nullable|array',
        'acs.*' => 'exists:acs,id',
        'producer' => 'nullable|string|max:30',
        'country_id' => 'nullable|exists:countries,id',
        'conc' => 'nullable|string|max:30',
        'reg_date' => 'nullable|date_format:Y-m-d',
        'reg_num' => 'nullable|string|max:30',
        'mix_ph' => 'nullable|numeric|max:14|min:0',
        'mix_rate' => 'nullable|numeric',
        'expiry' => 'nullable|integer',
        'precautions.ar' => 'nullable|max:255',
        'precautions.en' => 'nullable|max:255',
        'notes.ar' => 'nullable|max:255',
        'notes.en' => 'nullable|max:255',
    ];

    protected $appends = ['withdrawal_days'];

    public function getWithdrawalDaysAttribute()
    {
        return $this->acs()->max('withdrawal_days');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function product()
    {
        return $this->hasOne(\App\Models\Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function acs()
    {
        return $this->belongsToMany(\App\Models\Ac::class);
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
