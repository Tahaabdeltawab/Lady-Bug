<?php

namespace App\Repositories;

use App\Models\Fertilizer;
use App\Repositories\BaseRepository;

/**
 * Class FertilizerRepository
 * @package App\Repositories
 * @version September 11, 2022, 6:59 pm EET
*/

class FertilizerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'nut_elem_value_id',
        'dosage_form',
        'producer',
        'country_id',
        'addition_way',
        'conc',
        'reg_date',
        'reg_num',
        'mix_ph',
        'usage_rate',
        'expiry',
        'precautions',
        'notes'
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
        return Fertilizer::class;
    }
}
