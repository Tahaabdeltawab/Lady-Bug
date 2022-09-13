<?php

namespace App\Repositories;

use App\Models\Ac;
use App\Repositories\BaseRepository;

/**
 * Class AcRepository
 * @package App\Repositories
 * @version September 11, 2022, 6:03 pm EET
*/

class AcRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'who_class',
        'withdrawal_days',
        'precautions'
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
        return Ac::class;
    }
}
