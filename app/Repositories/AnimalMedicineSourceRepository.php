<?php

namespace App\Repositories;

use App\Models\AnimalMedicineSource;
use App\Repositories\BaseRepository;

/**
 * Class AnimalMedicineSourceRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:48 pm EET
*/

class AnimalMedicineSourceRepository extends BaseRepository
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
        return AnimalMedicineSource::class;
    }
}
