<?php

namespace App\Repositories;

use App\Models\Business;
use App\Repositories\BaseRepository;

/**
 * Class BusinessRepository
 * @package App\Repositories
 * @version September 10, 2022, 5:36 pm EET
*/

class BusinessRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'business_field_id',
        'description',
        'main_img',
        'cover_img',
        'com_name',
        'status',
        'mobile',
        'whatsapp',
        'lat',
        'lon',
        'country_id',
        'privacy'
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
        return Business::class;
    }
}
