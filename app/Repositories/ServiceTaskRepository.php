<?php

namespace App\Repositories;

use App\Models\ServiceTask;
use App\Repositories\BaseRepository;

/**
 * Class ServiceTaskRepository
 * @package App\Repositories
 * @version March 4, 2021, 1:34 am EET
*/

class ServiceTaskRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        // 'name',
        'start_at',
        'notify_at',
        'farm_id',
        'service_table_id',
        'task_type_id',
        'quantity',
        'quantity_unit_id',
        'due_at',
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
        return ServiceTask::class;
    }
}
