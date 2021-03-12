<?php

namespace App\Repositories;

use App\Models\FarmActivityType;
use App\Repositories\BaseRepository;

/**
 * Class FarmActivityTypeRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:26 pm EET
*/

class FarmActivityTypeRepository extends BaseRepository
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
        return FarmActivityType::class;
    }
}
