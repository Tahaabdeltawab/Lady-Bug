<?php

namespace App\Repositories;

use App\Models\SoilType;
use App\Repositories\BaseRepository;

/**
 * Class SoilTypeRepository
 * @package App\Repositories
 * @version March 4, 2021, 12:31 am EET
*/

class SoilTypeRepository extends BaseRepository
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
        return SoilType::class;
    }
}
