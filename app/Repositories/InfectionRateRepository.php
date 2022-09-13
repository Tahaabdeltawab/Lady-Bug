<?php

namespace App\Repositories;

use App\Models\InfectionRate;
use App\Repositories\BaseRepository;

/**
 * Class InfectionRateRepository
 * @package App\Repositories
 * @version September 9, 2022, 7:30 pm EET
*/

class InfectionRateRepository extends BaseRepository
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
        return InfectionRate::class;
    }
}
