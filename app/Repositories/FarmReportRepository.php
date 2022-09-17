<?php

namespace App\Repositories;

use App\Models\FarmReport;
use App\Repositories\BaseRepository;

/**
 * Class FarmReportRepository
 * @package App\Repositories
 * @version September 13, 2022, 7:32 pm EET
*/

class FarmReportRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'farm_id',
        'farmed_type_stage_id',
        'lat',
        'lon',
        'fertilization_start_date',
        'fertilization_unit',
        'notes'
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
        return FarmReport::class;
    }
}
