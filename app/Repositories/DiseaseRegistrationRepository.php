<?php

namespace App\Repositories;

use App\Models\DiseaseRegistration;
use App\Repositories\BaseRepository;

/**
 * Class DiseaseRegistrationRepository
 * @package App\Repositories
 * @version September 16, 2022, 2:50 pm EET
*/

class DiseaseRegistrationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'disease_id',
        'expected_name',
        'status',
        'discovery_date',
        'user_id',
        'farm_id',
        'farm_report_id',
        'infection_rate_id',
        'lat',
        'lon',
        'country_id'
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
        return DiseaseRegistration::class;
    }
}
