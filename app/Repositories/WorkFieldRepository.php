<?php

namespace App\Repositories;

use App\Models\WorkField;
use App\Repositories\BaseRepository;

/**
 * Class WorkFieldRepository
 * @package App\Repositories
 * @version August 28, 2022, 6:02 pm EET
*/

class WorkFieldRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title'
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
        return WorkField::class;
    }
}
