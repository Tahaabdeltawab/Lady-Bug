<?php

namespace App\Repositories;

use App\Models\OfflineConsultancyPlan;
use App\Repositories\BaseRepository;

/**
 * Class OfflineConsultancyPlanRepository
 * @package App\Repositories
 * @version August 28, 2022, 5:55 pm EET
*/

class OfflineConsultancyPlanRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'consultancy_profile_id',
        'address',
        'date',
        'visit_price',
        'year_price',
        'two_year_price'
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
        return OfflineConsultancyPlan::class;
    }
}
