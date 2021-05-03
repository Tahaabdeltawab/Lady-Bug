<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Overtrue\LaravelFavorite\Traits\Favoriteable;

/**
 * @SWG\Definition(
 *      definition="FarmedType",
 *      required={"name", "farm_activity_type_id"},
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
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="farm_activity_type_id",
 *          description="farm_activity_type_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class FarmedType extends Model implements TranslatableContract
{
    use /*SoftDeletes,*/ Translatable/* , Favoriteable */;

    public $translatedAttributes = ['name'];

    public $table = 'farmed_types';


    protected $dates = ['deleted_at'];



    public $fillable = [
        // 'name',
        'farm_activity_type_id',
        // 'photo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'name' => 'string',
        'farm_activity_type_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_ar_localized' => 'required|max:200',
        'name_en_localized' => 'required|max:200',
        'farm_activity_type_id' => 'required',
        'farming_temperature' => 'nullable|numeric',
        'flowering_temperature' => 'nullable|numeric',
        'maturity_temperature' => 'nullable|numeric',
        'humidity' => 'nullable|numeric',
        'flowering_time' => 'nullable|integer',
        'maturity_time' => 'nullable|integer',
        'photo' => 'nullable|max:2000|mimes:jpeg,jpg,png',
    ];

    public function farm_activity_type(){
        return $this->belongsTo(FarmActivityType::class);
    }

    public function favoriters()
    {
        return $this->morphToMany(User::class, 'favoriteable', 'favorites', 'favoriteable_id', 'user_id');
    }

    // public function photo()
    // {
    //     return $this->belongsTo(Asset::class, 'photo_id');
    // }

    public function asset()
    {
        return $this->morphOne(Asset::class, 'assetable');
    }

    public function farmed_type_classes()
    {
        return $this->hasMany(FarmedTypeClass::class);
    }

}
