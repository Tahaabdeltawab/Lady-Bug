<?php

namespace App\Repositories;

use App\Models\FarmedTypeNutVal;
use App\Repositories\BaseRepository;

/**
 * Class FarmedTypeNutValRepository
 * @package App\Repositories
 * @version September 10, 2022, 1:52 pm EET
*/

class FarmedTypeNutValRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'farmed_type_id',
        'calories',
        'total_fat',
        'sat_fat',
        'cholesterol',
        'na',
        'k',
        'total_carb',
        'dietary_fiber',
        'sugar',
        'protein',
        'v_c',
        'fe',
        'v_b6',
        'mg',
        'ca',
        'v_d',
        'cobalamin'
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
        return FarmedTypeNutVal::class;
    }
}
