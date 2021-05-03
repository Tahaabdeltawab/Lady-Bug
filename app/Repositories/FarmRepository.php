<?php

namespace App\Repositories;

use App\Models\Farm;
use App\Repositories\BaseRepository;

/**
 * Class FarmRepository
 * @package App\Repositories
 * @version March 4, 2021, 12:42 pm EET
*/

class FarmRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'admin_id',
        'real',
        'code',
        'archived',
        'location',
        'farming_date',
        'farming_compatibility',
        'home_plant_pot_size_id',
        'area',
        'area_unit_id',
        'farm_activity_type_id',
        'farmed_type_id',
        'farmed_type_class_id',
        'farmed_number',
        'breeding_purpose_id',
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Farm::class;
    }
}
