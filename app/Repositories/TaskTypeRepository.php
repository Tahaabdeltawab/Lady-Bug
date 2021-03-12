<?php

namespace App\Repositories;

use App\Models\TaskType;
use App\Repositories\BaseRepository;

/**
 * Class TaskTypeRepository
 * @package App\Repositories
 * @version March 7, 2021, 5:09 am EET
*/

class TaskTypeRepository extends BaseRepository
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
        return TaskType::class;
    }
}
