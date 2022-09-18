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
        'name_ar_localized' => 'required|max:200',
        'name_en_localized' => 'required|max:200',
        'farm_activity_type_id' => 'required',
        'farming_temperature' => 'nullable|numeric',
        'flowering_temperature' => 'nullable|numeric',
        'maturity_temperature' => 'nullable|numeric',
        'humidity' => 'nullable|numeric',
        'flowering_time' => 'nullable|integer',
        'maturity_time' => 'nullable|integer',
        'photo' => 'nullable|max:2000|mimes:jpeg,jpg,png',
    ];


    // // // // MUTATORS // // // //
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
    // // // // END MUTATORS // // // //



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
        return $this->belongsToMany(Disease::class)->using(DiseaseFarmedType::class)->wherePivot('sensitive', 1);
    }

    public function resistant_diseases()
    {
        return $this->belongsToMany(Disease::class)->using(DiseaseFarmedType::class)->wherePivot('sensitive', 0);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
