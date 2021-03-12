<?php

namespace App\Repositories;

use App\Models\ChemicalFertilizerSource;
use App\Repositories\BaseRepository;

/**
 * Class ChemicalFertilizerSourceRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:27 pm EET
*/

class ChemicalFertilizerSourceRepository extends BaseRepository
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
        return ChemicalFertilizerSource::class;
    }
}
