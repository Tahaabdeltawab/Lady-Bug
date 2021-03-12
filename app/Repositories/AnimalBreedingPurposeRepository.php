<?php

namespace App\Repositories;

use App\Models\AnimalBreedingPurpose;
use App\Repositories\BaseRepository;

/**
 * Class AnimalBreedingPurposeRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:29 pm EET
*/

class AnimalBreedingPurposeRepository extends BaseRepository
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
        return AnimalBreedingPurpose::class;
    }
}
