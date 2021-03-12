<?php

namespace App\Repositories;

use App\Models\SeedlingSource;
use App\Repositories\BaseRepository;

/**
 * Class SeedlingSourceRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:51 pm EET
*/

class SeedlingSourceRepository extends BaseRepository
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
        return SeedlingSource::class;
    }
}
