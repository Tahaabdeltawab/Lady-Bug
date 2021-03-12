<?php

namespace App\Repositories;

use App\Models\SaltDetail;
use App\Repositories\BaseRepository;

/**
 * Class SaltDetailRepository
 * @package App\Repositories
 * @version March 3, 2021, 11:46 pm EET
*/

class SaltDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'saltable_type',
        'PH',
        'CO3',
        'HCO3',
        'Cl',
        'SO4',
        'Ca',
        'Mg',
        'K',
        'Na',
        'Na2CO3'
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
        return SaltDetail::class;
    }
}
