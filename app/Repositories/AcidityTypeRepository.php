<?php

namespace App\Repositories;

use App\Models\AcidityType;
use App\Repositories\BaseRepository;

/**
 * Class AcidityTypeRepository
 * @package App\Repositories
 * @version March 13, 2021, 8:10 pm EET
*/

class AcidityTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
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
        return AcidityType::class;
    }
}
