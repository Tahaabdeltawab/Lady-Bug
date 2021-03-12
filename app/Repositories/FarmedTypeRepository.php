<?php

namespace App\Repositories;

use App\Models\FarmedType;
use App\Repositories\BaseRepository;

/**
 * Class FarmedTypeRepository
 * @package App\Repositories
 * @version March 4, 2021, 12:46 am EET
*/

class FarmedTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'farm_activity_type_id'
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
        return FarmedType::class;
    }
}
