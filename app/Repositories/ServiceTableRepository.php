<?php

namespace App\Repositories;

use App\Models\ServiceTable;
use App\Repositories\BaseRepository;

/**
 * Class ServiceTableRepository
 * @package App\Repositories
 * @version March 4, 2021, 1:27 am EET
*/

class ServiceTableRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'farm_id'
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
        return ServiceTable::class;
    }
}
