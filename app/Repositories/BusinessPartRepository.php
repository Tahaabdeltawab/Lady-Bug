<?php

namespace App\Repositories;

use App\Models\BusinessPart;
use App\Repositories\BaseRepository;

/**
 * Class BusinessPartRepository
 * @package App\Repositories
 * @version September 14, 2022, 2:26 pm EET
*/

class BusinessPartRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'business_id',
        'title',
        'description',
        'date',
        'done',
        'type'
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
        return BusinessPart::class;
    }
}
