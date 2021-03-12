<?php

namespace App\Repositories;

use App\Models\Information;
use App\Repositories\BaseRepository;

/**
 * Class InformationRepository
 * @package App\Repositories
 * @version March 4, 2021, 12:25 am EET
*/

class InformationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'content'
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
        return Information::class;
    }
}
