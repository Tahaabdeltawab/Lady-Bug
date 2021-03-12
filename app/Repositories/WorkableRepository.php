<?php

namespace App\Repositories;

use App\Models\Workable;
use App\Repositories\BaseRepository;

/**
 * Class WorkableRepository
 * @package App\Repositories
 * @version February 26, 2021, 5:19 am EET
*/

class WorkableRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'worker_id',
        'workable_id',
        'workable_type',
        'status'
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
        return Workable::class;
    }
}
