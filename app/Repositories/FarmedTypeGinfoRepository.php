<?php

namespace App\Repositories;

use App\Models\FarmedTypeGinfo;
use App\Repositories\BaseRepository;

/**
 * Class FarmedTypeGinfoRepository
 * @package App\Repositories
 * @version March 4, 2021, 1:42 am EET
*/

class FarmedTypeGinfoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'content',
        'farmed_type_id',
        'farmed_type_stage_id'
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
        return FarmedTypeGinfo::class;
    }
}
