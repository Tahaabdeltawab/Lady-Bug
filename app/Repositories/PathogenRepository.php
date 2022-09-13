<?php

namespace App\Repositories;

use App\Models\Pathogen;
use App\Repositories\BaseRepository;

/**
 * Class PathogenRepository
 * @package App\Repositories
 * @version September 11, 2022, 6:23 pm EET
*/

class PathogenRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'pathogen_type_id',
        'bio_control',
        'ch_control'
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
        return Pathogen::class;
    }
}
