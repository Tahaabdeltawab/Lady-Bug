<?php

namespace App\Repositories;

use App\Models\FarmedTypeExtras;
use App\Repositories\BaseRepository;

/**
 * Class FarmedTypeExtrasRepository
 * @package App\Repositories
 * @version September 11, 2022, 4:02 pm EET
*/

class FarmedTypeExtrasRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'farmed_type_id',
        'irrigation_rate_id',
        'seedling_type',
        'scientific_name',
        'history',
        'producer',
        'description',
        'cold_hours',
        'illumination_hours',
        'seeds_rate',
        'production_rate'
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
        return FarmedTypeExtras::class;
    }
}
