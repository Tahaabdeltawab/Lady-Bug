<?php

namespace App\Repositories;

use App\Models\FarmingWay;
use App\Repositories\BaseRepository;

/**
 * Class FarmingWayRepository
 * @package App\Repositories
 * @version March 4, 2021, 12:33 am EET
*/

class FarmingWayRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'type'
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
        return FarmingWay::class;
    }
}
