<?php

namespace App\Repositories;

use App\Models\Career;
use App\Repositories\BaseRepository;

/**
 * Class CareerRepository
 * @package App\Repositories
 * @version September 9, 2022, 6:15 pm EET
*/

class CareerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'title',
        'date'
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
        return Career::class;
    }
}
