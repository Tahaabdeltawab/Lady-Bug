<?php

namespace App\Repositories;

use App\Models\BuyingNote;
use App\Repositories\BaseRepository;

/**
 * Class BuyingNoteRepository
 * @package App\Repositories
 * @version March 4, 2021, 12:17 am EET
*/

class BuyingNoteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'content'
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
        return BuyingNote::class;
    }
}
