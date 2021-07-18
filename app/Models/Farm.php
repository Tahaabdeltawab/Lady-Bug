<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="Farm",
 *      required={"real", "archived", "location", "farming_date", "farming_compatibility", "area", "area_unit_id", "farm_activity_type_id", "farmed_type_id", "soil_type_id", "soil_detail_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="real",
 *          description="real",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="archived",
 *          description="archived",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="location",
 *          description="location",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="farming_date",
 *          description="farming_date",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="farming_compatibility",
 *          description="farming_compatibility",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="home_plant_pot_size",
 *          description="home_plant_pot_size",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="area",
 *          description="area",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="area_unit_id",
 *          description="area_unit_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="farm_activity_type_id",
 *          description="farm_activity_type_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="farmed_type_id",
 *          description="farmed_type_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="animal_breeding_purpose_id",
 *          description="animal_breeding_purpose_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="home_plant_illuminating_source_id",
 *          description="home_plant_illuminating_source_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="farming_method_id",
 *          description="farming_method_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="farming_way_id",
 *          description="farming_way_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="irrigation_way_id",
 *          description="irrigation_way_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="soil_type_id",
 *          description="soil_type_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="soil_detail_id",
 *          description="soil_detail_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="irrigation_water_detail_id",
 *          description="irrigation_water_detail_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="animal_drink_water_salt_detail_id",
 *          description="animal_drink_water_salt_detail_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
class Farm extends Team
{
  // use SoftDeletes;


    public $table = 'farms';


    protected $dates = ['deleted_at'];



    public $fillable = [
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

    public function workers()
    {
        return $this->morphToMany(User::class, 'workable', 'workables', 'workable_id', 'worker_id')->using(Workable::class)->withPivot('id', 'status')->withTimestamps();
    }

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


    // needs relationships tables
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function service_tables()
    {
        return $this->hasMany(ServiceTable::class);
    }

    public function service_tasks()
    {
        return $this->hasMany(ServiceTask::class);
    }

    public function animal_fodder_types()
    {
        return $this->belongsToMany(AnimalFodderType::class);
    }

    public function animal_fodder_sources()
    {
        return $this->belongsToMany(AnimalFodderSource::class);
    }

    public function animal_medicine_sources()
    {
        return $this->belongsToMany(AnimalMedicineSource::class);
    }

    public function seedling_sources()
    {
        return $this->belongsToMany(SeedlingSource::class);
    }

    public function chemical_fertilizer_sources()
    {
        return $this->belongsToMany(ChemicalFertilizerSource::class);
    }









}
