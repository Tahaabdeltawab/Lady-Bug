<?php

namespace App\Repositories;

use App\Models\BusinessBranch;
use App\Repositories\BaseRepository;

/**
 * Class BusinessBranchRepository
 * @package App\Repositories
 * @version September 10, 2022, 5:48 pm EET
*/

class BusinessBranchRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'address'
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
        return BusinessBranch::class;
    }
}
