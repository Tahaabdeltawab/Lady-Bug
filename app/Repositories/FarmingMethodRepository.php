<?php

namespace App\Repositories;

use App\Models\FarmingMethod;
use App\Repositories\BaseRepository;

/**
 * Class FarmingMethodRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:31 pm EET
*/

class FarmingMethodRepository extends BaseRepository
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
        return FarmingMethod::class;
    }
}
