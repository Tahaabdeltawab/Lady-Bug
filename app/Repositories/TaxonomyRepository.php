<?php

namespace App\Repositories;

use App\Models\Taxonomy;
use App\Repositories\BaseRepository;

/**
 * Class TaxonomyRepository
 * @package App\Repositories
 * @version September 11, 2022, 4:08 pm EET
*/

class TaxonomyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'farmed_type_id',
        'kingdom',
        'domain',
        'phylum',
        'subphylum',
        'superclass',
        'class',
        'order',
        'family',
        'genus',
        'species'
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
        return Taxonomy::class;
    }
}
