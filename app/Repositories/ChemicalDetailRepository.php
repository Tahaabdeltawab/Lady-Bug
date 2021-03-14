<?php

namespace App\Repositories;

use App\Models\ChemicalDetail;
use App\Repositories\BaseRepository;

/**
 * Class ChemicalDetailRepository
 * @package App\Repositories
 * @version March 4, 2021, 1:48 am EET
*/

class ChemicalDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'acidity',
        'acidity_value',
        'acidity_unit_id',
        'salt_type_id',
        'salt_concentration_value',
        'salt_concentration_unit_id',
        'salt_detail_id'
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
        return ChemicalDetail::class;
    }
}
