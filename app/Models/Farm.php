<?php

namespace App\Models;

use Eloquent as Model;


class Farm extends Model
{

    public $table = 'farms';



    public $fillable = [
        'business_id',
        'admin_id',
        'code',
        'real',
        'archived',
        'location_id',
        'farming_date',
        'farming_compatibility',
        'home_plant_pot_size_id',
        'area',
        'area_unit_id',
        'farm_activity_type_id',
        'farmed_type_id',
        'farmed_type_class_id',
        'farmed_number',
        'animal_breeding_purpose_id',
        'home_plant_illuminating_source_id',
        'farming_method_id',
        'farming_way_id',
        'irrigation_way_id',
        'soil_type_id',
        'soil_detail_id',
        'irrigation_water_detail_id',
        'animal_drink_water_salt_detail_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'business_id' => 'integer',
        'admin_id' => 'integer',
        'code' => 'string',
        'real' => 'boolean',
        'archived' => 'boolean',
        'location_id' => 'integer',
        'home_plant_pot_size_id' => 'integer',
        'area' => 'double',
        'area_unit_id' => 'integer',
        'farm_activity_type_id' => 'integer',
        'farmed_type_id' => 'integer',
        'farmed_type_class_id' => 'integer',
        'animal_breeding_purpose_id' => 'integer',
        'home_plant_illuminating_source_id' => 'integer',
        'farming_method_id' => 'integer',
        'farming_way_id' => 'integer',
        'irrigation_way_id' => 'integer',
        'soil_type_id' => 'integer',
        'soil_detail_id' => 'integer',
        'irrigation_water_detail_id' => 'integer',
        'animal_drink_water_salt_detail_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];


    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function farm_activity_type()
    {
        return $this->belongsTo(FarmActivityType::class);
    }

    public function farmed_type()
    {
        return $this->belongsTo(FarmedType::class);
    }

    public function farmed_type_class()
    {
        return $this->belongsTo(FarmedTypeClass::class);
    }

    public function animal_breeding_purpose()
    {
        return $this->belongsTo(AnimalBreedingPurpose::class, 'animal_breeding_purpose_id');
    }

    public function home_plant_illuminating_source()
    {
        return $this->belongsTo(HomePlantIlluminatingSource::class);
    }

    public function home_plant_pot_size()
    {
        return $this->belongsTo(HomePlantPotSize::class);
    }

    public function farming_method()
    {
        return $this->belongsTo(FarmingMethod::class);
    }

    public function farming_way()
    {
        return $this->belongsTo(FarmingWay::class);
    }

    public function irrigation_way()
    {
        return $this->belongsTo(IrrigationWay::class);
    }

    public function soil_type()
    {
        return $this->belongsTo(SoilType::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function soil_detail()
    {
        return $this->belongsTo(ChemicalDetail::class);
    }

    public function irrigation_water_detail()
    {
        return $this->belongsTo(ChemicalDetail::class);
    }

    public function animal_drink_water_salt_detail()
    {
        return $this->belongsTo(SaltDetail::class);
    }

    public function area_unit()
    {
        return $this->belongsTo(MeasuringUnit::class, 'area_unit_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function farm_reports()
    {
        return $this->hasMany(FarmReport::class);
    }

    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class);
    }

    // public function service_tables()
    // {
    //     return $this->hasMany(ServiceTable::class);
    // }

    // public function service_tasks()
    // {
    //     return $this->hasMany(ServiceTask::class);
    // }

    public function animal_fodder_types()
    {
        return $this->belongsToMany(AnimalFodderType::class);
    }

    public function animal_fodder_sources()
    {
        return $this->belongsToMany(Business::class, 'animal_fodder_source_farm', 'farm_id', 'animal_fodder_source_id');
    }

    public function animal_medicine_sources()
    {
        return $this->belongsToMany(Business::class, 'animal_medicine_source_farm', 'farm_id', 'animal_medicine_source_id');
    }

    public function seedling_sources()
    {
        return $this->belongsToMany(Business::class, 'farm_seedling_source', 'farm_id', 'seedling_source_id');
    }

    public function chemical_fertilizer_sources()
    {
        return $this->belongsToMany(Business::class, 'chemical_fertilizer_source_farm', 'farm_id', 'chemical_fertilizer_source_id');
    }

}
