<?php

namespace App\Repositories;

use App\Models\FarmedTypeClass;
use App\Repositories\BaseRepository;

/**
 * Class FarmedTypeClassRepository
 * @package App\Repositories
 * @version March 4, 2021, 1:38 am EET
*/

class FarmedTypeClassRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'farmed_type_id'
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
        return FarmedTypeClass::class;
    }
}
