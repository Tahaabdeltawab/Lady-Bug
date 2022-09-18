<?php

namespace App\Repositories;

use App\Models\ProductType;
use App\Repositories\BaseRepository;

/**
 * Class ProductTypeRepository
 * @package App\Repositories
 * @version September 17, 2022, 6:30 pm EET
*/

class ProductTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return ProductType::class;
    }
}
