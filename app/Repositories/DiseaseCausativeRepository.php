<?php

namespace App\Repositories;

use App\Models\DiseaseCausative;
use App\Repositories\BaseRepository;

/**
 * Class DiseaseCausativeRepository
 * @package App\Repositories
 * @version September 11, 2022, 6:16 pm EET
*/

class DiseaseCausativeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'disease_id',
        'temp_gt',
        'temp_lt',
        'humidity_gt',
        'humidity_lt',
        'ph_gt',
        'ph_lt',
        'soil_salts_gt',
        'soil_salts_lt',
        'water_salts_gt',
        'water_salts_lt'
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
        return DiseaseCausative::class;
    }
}
