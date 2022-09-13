<?php

namespace App\Repositories;

use App\Models\PathogenType;
use App\Repositories\BaseRepository;

/**
 * Class PathogenTypeRepository
 * @package App\Repositories
 * @version September 9, 2022, 7:36 pm EET
*/

class PathogenTypeRepository extends BaseRepository
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
        return PathogenType::class;
    }
}
