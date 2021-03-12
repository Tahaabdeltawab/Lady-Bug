<?php

namespace App\Repositories;

use App\Models\WorkableType;
use App\Repositories\BaseRepository;

/**
 * Class WorkableTypeRepository
 * @package App\Repositories
 * @version February 26, 2021, 1:58 am EET
*/

class WorkableTypeRepository extends BaseRepository
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
        return WorkableType::class;
    }
}
