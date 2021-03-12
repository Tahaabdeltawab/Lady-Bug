<?php

namespace App\Repositories;

use App\Models\FarmedTypeStage;
use App\Repositories\BaseRepository;

/**
 * Class FarmedTypeStageRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:22 pm EET
*/

class FarmedTypeStageRepository extends BaseRepository
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
        return FarmedTypeStage::class;
    }
}
