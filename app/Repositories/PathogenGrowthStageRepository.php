<?php

namespace App\Repositories;

use App\Models\PathogenGrowthStage;
use App\Repositories\BaseRepository;

/**
 * Class PathogenGrowthStageRepository
 * @package App\Repositories
 * @version September 9, 2022, 7:34 pm EET
*/

class PathogenGrowthStageRepository extends BaseRepository
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
        return PathogenGrowthStage::class;
    }
}
