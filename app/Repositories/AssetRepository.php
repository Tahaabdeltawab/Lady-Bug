<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Repositories\BaseRepository;

/**
 * Class AssetRepository
 * @package App\Repositories
 * @version February 13, 2021, 8:59 pm EET
*/

class AssetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'asset_name',
        'asset_url',
        'asset_size',
        'asset_mime',
        'assetable_type'
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
        return Asset::class;
    }
}
