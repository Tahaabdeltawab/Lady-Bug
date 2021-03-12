<?php

namespace App\Repositories;

use App\Models\IrrigationWay;
use App\Repositories\BaseRepository;

/**
 * Class IrrigationWayRepository
 * @package App\Repositories
 * @version March 4, 2021, 12:32 am EET
*/

class IrrigationWayRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return IrrigationWay::class;
    }
}
