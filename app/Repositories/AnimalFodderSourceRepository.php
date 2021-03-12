<?php

namespace App\Repositories;

use App\Models\AnimalFodderSource;
use App\Repositories\BaseRepository;

/**
 * Class AnimalFodderSourceRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:18 pm EET
*/

class AnimalFodderSourceRepository extends BaseRepository
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
        return AnimalFodderSource::class;
    }
}
