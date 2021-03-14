<?php

namespace App\Repositories;

use App\Models\SaltType;
use App\Repositories\BaseRepository;

/**
 * Class SaltTypeRepository
 * @package App\Repositories
 * @version March 13, 2021, 3:14 am EET
*/

class SaltTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type'
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
        return SaltType::class;
    }
}
