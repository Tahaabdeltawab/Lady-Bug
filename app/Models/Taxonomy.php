<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Taxonomy
 * @package App\Models
 * @version September 11, 2022, 4:08 pm EET
 *
 * @property \App\Models\FarmedType $farmedType
 * @property integer $farmed_type_id
 * @property string $kingdom
 * @property string $domain
 * @property string $phylum
 * @property string $subphylum
 * @property string $superclass
 * @property string $class
 * @property string $order
 * @property string $family
 * @property string $genus
 * @property string $species
 */
class Taxonomy extends Model
{


    public $table = 'taxonomies';
	public $timestamps = false;




    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'farmed_type_id' => 'integer',
        'kingdom' => 'string',
        'domain' => 'string',
        'phylum' => 'string',
        'subphylum' => 'string',
        'superclass' => 'string',
        'class' => 'string',
        'order' => 'string',
        'family' => 'string',
        'genus' => 'string',
        'species' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'farmed_type_id' => 'required|exists:farmed_types,id',
        'kingdom' => 'nullable|max:30',
        'domain' => 'nullable|max:30',
        'phylum' => 'nullable|max:30',
        'subphylum' => 'nullable|max:30',
        'superclass' => 'nullable|max:30',
        'class' => 'nullable|max:30',
        'order' => 'nullable|max:30',
        'family' => 'nullable|max:30',
        'genus' => 'nullable|max:30',
        'species' => 'nullable|max:30'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function farmedType()
    {
        return $this->belongsTo(\App\Models\FarmedType::class);
    }
}
