<?php

namespace App\Repositories;

use App\Models\Insecticide;
use App\Repositories\BaseRepository;

/**
 * Class InsecticideRepository
 * @package App\Repositories
 * @version September 11, 2022, 6:53 pm EET
*/

class InsecticideRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'dosage_form',
        'producer',
        'country_id',
        'conc',
        'reg_date',
        'reg_num',
        'mix_ph',
        'mix_rate',
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
        return Insecticide::class;
    }
}
