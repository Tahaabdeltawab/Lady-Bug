<?php

namespace App\Repositories;

use App\Models\BusinessField;
use App\Repositories\BaseRepository;

/**
 * Class BusinessFieldRepository
 * @package App\Repositories
 * @version September 10, 2022, 4:44 pm EET
*/

class BusinessFieldRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return BusinessField::class;
    }
}
