<?php

namespace App\Repositories;

use App\Models\Location;
use App\Repositories\BaseRepository;

/**
 * Class LocationRepository
 * @package App\Repositories
 * @version March 13, 2021, 8:09 pm EET
*/

class LocationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'latitude',
        'longitude',
        'country',
        'city',
        'district',
        'details'
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
        return Location::class;
    }
}
