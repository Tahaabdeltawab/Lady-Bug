<?php

namespace App\Repositories;

use App\Models\Visited;
use App\Repositories\BaseRepository;

/**
 * Class VisitedRepository
 * @package App\Repositories
 * @version September 9, 2022, 6:17 pm EET
*/

class VisitedRepository extends BaseRepository
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
        return Visited::class;
    }
}
