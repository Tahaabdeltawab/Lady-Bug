<?php

namespace App\Repositories;

use App\Models\WorkableRole;
use App\Repositories\BaseRepository;

/**
 * Class WorkableRoleRepository
 * @package App\Repositories
 * @version February 25, 2021, 10:06 pm EET
*/

class WorkableRoleRepository extends BaseRepository
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
        return WorkableRole::class;
    }
}
