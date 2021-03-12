<?php

namespace App\Repositories;

use App\Models\WeatherNote;
use App\Repositories\BaseRepository;

/**
 * Class WeatherNoteRepository
 * @package App\Repositories
 * @version March 4, 2021, 12:30 am EET
*/

class WeatherNoteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'content',
        'user_id'
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
        return WeatherNote::class;
    }
}
