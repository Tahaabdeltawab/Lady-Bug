<?php

namespace App\Repositories;

use App\Models\Residence;
use App\Repositories\BaseRepository;

/**
 * Class ResidenceRepository
 * @package App\Repositories
 * @version September 9, 2022, 6:16 pm EET
*/

class ResidenceRepository extends BaseRepository
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
        return Residence::class;
    }
}
