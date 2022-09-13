<?php

namespace App\Repositories;

use App\Models\IrrigationRate;
use App\Repositories\BaseRepository;

/**
 * Class IrrigationRateRepository
 * @package App\Repositories
 * @version September 9, 2022, 7:29 pm EET
*/

class IrrigationRateRepository extends BaseRepository
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
        return IrrigationRate::class;
    }
}
