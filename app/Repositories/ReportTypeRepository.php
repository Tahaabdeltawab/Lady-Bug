<?php

namespace App\Repositories;

use App\Models\ReportType;
use App\Repositories\BaseRepository;

/**
 * Class ReportTypeRepository
 * @package App\Repositories
 * @version August 20, 2021, 1:21 am EET
*/

class ReportTypeRepository extends BaseRepository
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
        return ReportType::class;
    }
}
