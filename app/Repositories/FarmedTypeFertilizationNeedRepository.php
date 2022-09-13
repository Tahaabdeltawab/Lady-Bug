<?php

namespace App\Repositories;

use App\Models\FarmedTypeFertilizationNeed;
use App\Repositories\BaseRepository;

/**
 * Class FarmedTypeFertilizationNeedRepository
 * @package App\Repositories
 * @version September 10, 2022, 2:09 pm EET
*/

class FarmedTypeFertilizationNeedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'farmed_type_id',
        'stage',
        'per',
        'nut_elem_value_id'
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
        return FarmedTypeFertilizationNeed::class;
    }
}
