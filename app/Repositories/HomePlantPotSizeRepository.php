<?php

namespace App\Repositories;

use App\Models\HomePlantPotSize;
use App\Repositories\BaseRepository;

/**
 * Class HomePlantPotSizeRepository
 * @package App\Repositories
 * @version March 17, 2021, 6:25 pm EET
*/

class HomePlantPotSizeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'size'
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
        return HomePlantPotSize::class;
    }
}
