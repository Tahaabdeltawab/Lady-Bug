<?php

namespace App\Repositories;

use App\Models\HomePlantIlluminatingSource;
use App\Repositories\BaseRepository;

/**
 * Class HomePlantIlluminatingSourceRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:30 pm EET
*/

class HomePlantIlluminatingSourceRepository extends BaseRepository
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
        return HomePlantIlluminatingSource::class;
    }
}
