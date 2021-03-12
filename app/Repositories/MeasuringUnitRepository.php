<?php

namespace App\Repositories;

use App\Models\MeasuringUnit;
use App\Repositories\BaseRepository;

/**
 * Class MeasuringUnitRepository
 * @package App\Repositories
 * @version March 4, 2021, 12:02 am EET
*/

class MeasuringUnitRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'code',
        'measurable'
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
        return MeasuringUnit::class;
    }
}
