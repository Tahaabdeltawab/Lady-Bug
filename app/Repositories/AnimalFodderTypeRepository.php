<?php

namespace App\Repositories;

use App\Models\AnimalFodderType;
use App\Repositories\BaseRepository;

/**
 * Class AnimalFodderTypeRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:47 pm EET
*/

class AnimalFodderTypeRepository extends BaseRepository
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
        return AnimalFodderType::class;
    }
}
