<?php

namespace App\Repositories;

use App\Models\MarketingData;
use App\Repositories\BaseRepository;

/**
 * Class MarketingDataRepository
 * @package App\Repositories
 * @version September 11, 2022, 4:05 pm EET
*/

class MarketingDataRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'farmed_type_id',
        'year',
        'country_id',
        'production',
        'consumption',
        'export',
        'price_avg'
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
        return MarketingData::class;
    }
}
