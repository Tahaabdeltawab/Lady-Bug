<?php

namespace App\Repositories;

use App\Models\WorkablePermission;
use App\Repositories\BaseRepository;

/**
 * Class WorkablePermissionRepository
 * @package App\Repositories
 * @version February 25, 2021, 10:10 pm EET
*/

class WorkablePermissionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'workable_type_id'
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
        return WorkablePermission::class;
    }
}
