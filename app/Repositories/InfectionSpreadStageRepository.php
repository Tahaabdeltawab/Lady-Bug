<?php

namespace App\Repositories;

use App\Models\InfectionSpreadStage;
use App\Repositories\BaseRepository;

/**
 * Class InfectionSpreadStageRepository
 * @package App\Repositories
 * @version September 9, 2022, 7:33 pm EET
*/

class InfectionSpreadStageRepository extends BaseRepository
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
        return InfectionSpreadStage::class;
    }
}
