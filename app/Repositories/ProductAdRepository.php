<?php

namespace App\Repositories;

use App\Models\ProductAd;
use App\Repositories\BaseRepository;

/**
 * Class ProductAdRepository
 * @package App\Repositories
 * @version September 18, 2022, 1:04 pm EET
*/

class ProductAdRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'name',
        'description',
        'stacked'
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
        return ProductAd::class;
    }
}
