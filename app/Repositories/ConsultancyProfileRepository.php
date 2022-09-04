<?php

namespace App\Repositories;

use App\Models\ConsultancyProfile;
use App\Repositories\BaseRepository;

/**
 * Class ConsultancyProfileRepository
 * @package App\Repositories
 * @version August 28, 2022, 5:38 pm EET
*/

class ConsultancyProfileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'experience',
        'ar',
        'en',
        'consultancy_price',
        'month_consultancy_price',
        'year_consultancy_price',
        'free_consultancy_price'
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
        return ConsultancyProfile::class;
    }
}
