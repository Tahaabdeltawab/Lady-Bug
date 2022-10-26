<?php

namespace App\Models;

use Eloquent as Model;
// use Overtrue\LaravelFavorite\Traits\Favoriteable;


class FarmedType extends Model
{
    use \App\Traits\SpatieHasTranslations/* , Favoriteable */;

    public $translatable = ['name'];
	public $timestamps = false;

    public $table = 'farmed_types';



    public $fillable = [
        'name',
        'parent_id',
        'country_id',
        'farm_activity_type_id',
        'farming_temperature',
        'flowering_temperature',
        'maturity_temperature',
        'humidity',
        'flowering_time',
        'maturity_time',
        'suitable_soil_salts_concentration',
        'suitable_water_salts_concentration',
        'suitable_ph',
        'suitable_soil_types',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'farm_activity_type_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name.ar'                               => 'required|max:200',
        'name.en'                               => 'required|max:200',
        'farm_activity_type_id'                 => 'required',
        'parent_id'                             => 'nullable|exists:farmed_types,id',
        'country_id'                            => 'nullable|exists:countries,id',
        'photo'                                 => 'nullable|max:5000|image',
        'flowering_time'                        => 'nullable|integer', // number of days till flowering
        'maturity_time'                         => 'nullable|integer',  // number of days till maturity

        'farming_temperature'                   => 'nullable',
        'flowering_temperature'                 => 'nullable',
        'maturity_temperature'                  => 'nullable',
        // 'humidity'                              => 'nullable|array|size:2', // in the time of maturity
        // 'humidity.*'                            => 'nullable|numeric', // in the time of maturity
        'humidity'                              => 'nullable', // in the time of maturity
        'suitable_soil_salts_concentration'     => 'nullable',
        'suitable_water_salts_concentration'    => 'nullable',
        'suitable_ph'                           => 'nullable',
        'suitable_soil_types'                   => 'nullable',
    ];


    // MUTATORS
    public function getFarmingTemperatureAttribute($attr)
    {
        return json_decode($attr) ?? [];
    }

    public function getFloweringTemperatureAttribute($attr)
    {
        return json_decode($attr) ?? [];
    }

    public function getMaturityTemperatureAttribute($attr)
    {
        return json_decode($attr) ?? [];
    }

    public function getHumidityAttribute($attr)
    {
        return json_decode($attr) ?? [];
    }

    public function getSuitableSoilSaltsConcentrationAttribute($attr)
    {
        return json_decode($attr) ?? [];
    }

    public function getSuitableWaterSaltsConcentrationAttribute($attr)
    {
        return json_decode($attr) ?? [];
    }

    public function getSuitablePhAttribute($attr)
    {
        return json_decode($attr) ?? [];
    }

    public function getSuitableSoilTypesAttribute($attr)
    {
        return json_decode($attr) ?? [];
    }
    // END MUTATORS


    // SCOPES
    public function scopeGlobal($q)
    {
        return $q->whereNull('parent_id');
    }

    public function extra(){
        return $this->hasOne(FarmedTypeExtras::class);
    }

    public function taxonomy(){
        return $this->hasOne(Taxonomy::class);
    }

    public function nutVal(){
        return $this->hasOne(FarmedTypeNutVal::class);
    }

    public function marketing(){
        return $this->hasOne(MarketingData::class);
    }

    public function fneeds($farmed_type_stage_id = null)
    {
        return $this->hasMany(FarmedTypeFertilizationNeed::class)
            ->when($farmed_type_stage_id, function($q) use($farmed_type_stage_id){
                return $q->where('farmed_type_stage_id', $farmed_type_stage_id);
            });
    }

    public function popular_countries()
    {
        return $this->belongsToMany(Country::class)->wherePivot('popular', 1);
    }

    public function names_countries()
    {
        return $this->belongsToMany(Country::class)->withPivot('common_name')->wherePivotNotNull('common_name');
    }

    public function sensitive_diseases()
    {
        return $this->belongsToMany(Disease::class, 'sensitive_disease_farmed_type')->withPivot('farmed_type_stage_id');
    }

    public function resistant_diseases()
    {
        return $this->belongsToMany(Disease::class, 'resistant_disease_farmed_type');
    }



    public function farm_activity_type(){
        return $this->belongsTo(FarmActivityType::class);
    }

    public function favoriters()
    {
        return $this->morphToMany(User::class, 'favoriteable', 'favorites', 'favoriteable_id', 'user_id');
    }

    public function asset()
    {
        return $this->morphOne(Asset::class, 'assetable');
    }

    public function farmed_type_classes()
    {
        return $this->hasMany(FarmedTypeClass::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
