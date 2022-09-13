<?php

namespace App\Repositories;

use App\Models\NutElemValue;
use App\Repositories\BaseRepository;

/**
 * Class NutElemValueRepository
 * @package App\Repositories
 * @version September 10, 2022, 1:45 pm EET
*/

class NutElemValueRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'n',
        'p',
        'k',
        'fe',
        'b',
        'ca',
        'mg'
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
        return NutElemValue::class;
    }
}
