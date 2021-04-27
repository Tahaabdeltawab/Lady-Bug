<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="FarmedTypeGinfo",
 *      required={"title", "content", "farmed_type_id", "farmed_type_stage_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="content",
 *          description="content",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="farmed_type_id",
 *          description="farmed_type_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="farmed_type_stage_id",
 *          description="farmed_type_stage_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class FarmedTypeGinfo extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    public $translatedAttributes = ['title', 'content'];


    public $table = 'farmed_type_ginfos';


    protected $dates = ['deleted_at'];



    public $fillable = [
        // 'title',
        // 'content',
        'farmed_type_id',
        'farmed_type_stage_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'title' => 'string',
        // 'content' => 'string',
        'farmed_type_id' => 'integer',
        'farmed_type_stage_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }


    public function farmed_type_stage()
    {
        return $this->belongsTo(FarmedTypeStage::class);
    }


    public function farmed_type()
    {
        return $this->belongsTo(FarmedType::class);
    }

}
