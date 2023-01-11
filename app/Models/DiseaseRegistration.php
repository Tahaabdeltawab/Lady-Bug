<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class DiseaseRegistration
 * @package App\Models
 * @version September 16, 2022, 2:50 pm EET
 *
 * @property \App\Models\Disease $disease
 * @property \App\Models\User $user
 * @property \App\Models\Farm $farm
 * @property \App\Models\FarmReport $farmReport
 * @property \App\Models\InfectionRate $infectionRate
 * @property \App\Models\Country $country
 * @property integer $disease_id
 * @property string $expected_name
 * @property boolean $status
 * @property string $discovery_date
 * @property integer $user_id
 * @property integer $farm_id
 * @property integer $farm_report_id
 * @property integer $infection_rate_id
 * @property string $lat
 * @property string $lon
 * @property integer $country_id
 */
class DiseaseRegistration extends Model
{


    public $table = 'disease_registrations';




    public $fillable = [
        'disease_id',
        'expected_name',
        'status',
        'discovery_date',
        'user_id',
        'farm_id',
        'farm_report_id',
        'infection_rate_id',
        'country_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'disease_id' => 'integer',
        'expected_name' => 'string',
        'status' => 'boolean',
        'discovery_date' => 'date',
        'user_id' => 'integer',
        'farm_id' => 'integer',
        'farm_report_id' => 'integer',
        'infection_rate_id' => 'integer',
        'country_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'disease_id' => 'nullable|exists:diseases,id',
        'expected_name' => 'nullable',
        'status' => 'exclude',
        'discovery_date' => 'nullable',
        'farm_id' => 'nullable|exists:farms,id',
        'farm_report_id' => 'nullable|exists:farm_reports,id',
        'infection_rate_id' => 'nullable|exists:infection_rates,id',
        'country_id' => 'nullable|exists:countries,id'
    ];

    public static $update_rules = [
        'status' => 'required|boolean',
        'disease_id' => 'nullable|exists:diseases,id',
        'expected_name' => 'nullable',
        'discovery_date' => 'nullable',
        'infection_rate_id' => 'nullable|exists:infection_rates,id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function disease()
    {
        return $this->belongsTo(\App\Models\Disease::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farm()
    {
        return $this->belongsTo(\App\Models\Farm::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmReport()
    {
        return $this->belongsTo(\App\Models\FarmReport::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function infectionRate()
    {
        return $this->belongsTo(\App\Models\InfectionRate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    /**
     * SCOPES
     */

    public function scopeLocated($q){
        return $q->whereHas('farmReport', function($qq){
            return $qq->whereNotNull('farm_reports.location_id');
        });
    }

    public function scopeConfirmed($q){
        return $q->where('status', 1);
    }

    public function scopeNotconfirmed($q){
        return $q->where('status', 0);
    }

    public function scopeActive($q){
        return $q->confirmed()->located();
    }


}
