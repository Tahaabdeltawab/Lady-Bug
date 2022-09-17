<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\BaseRepository;

/**
 * Class TaskRepository
 * @package App\Repositories
 * @version September 16, 2022, 2:43 pm EET
*/

class TaskRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'farm_report_id',
        'farm_id',
        'business_id',
        'date',
        'week',
        'task_type_id',
        'insecticide_id',
        'fertilizer_id',
        'quantity',
        'quantity_unit',
        'done'
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
        return Task::class;
    }
}
