<?php

namespace App\Repositories;

use App\Models\RatingQuestion;
use App\Repositories\BaseRepository;

/**
 * Class RatingQuestionRepository
 * @package App\Repositories
 * @version August 15, 2022, 6:13 pm EET
*/

class RatingQuestionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'type'
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
        return RatingQuestion::class;
    }
}
