<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use willvincent\Rateable\Rateable;


/**
 * @SWG\Definition(
 *      definition="Product",
 *      required={"price", "description", "seller_id", "name", "city", "district", "seller_mobile", "sold"},
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
 *          property="price",
 *          description="price",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="seller_id",
 *          description="seller_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="city",
 *          description="city",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="district",
 *          description="district",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="seller_mobile",
 *          description="seller_mobile",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="sold",
 *          description="sold",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="other_links",
 *          description="other_links",
 *          type="string"
 *      )
 * )
 */
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Product extends Model implements TranslatableContract
{
    use /*SoftDeletes,*/ Translatable, Rateable;

    public $translatedAttributes = ['name', 'description'];

    public $table = 'products';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'price',
        'seller_id',
        // 'description',
        // 'name',
        'farmed_type_id',
        'city_id',
        'district_id',
        'seller_mobile',
        'sold',
        'other_links'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'price' => 'double',
        'description' => 'string',
        'seller_id' => 'integer',
        'name' => 'string',
        'farmed_type_id' => 'integer',
        'city_id' => 'integer',
        'district_id' => 'integer',
        'seller_mobile' => 'string',
        'sold' => 'boolean',
        'other_links' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function district()
    {
        return $this->belongsTo(District::class);
    }


    public function farmed_type()
    {
        return $this->belongsTo(FarmedType::class);
    }


    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function internal_assets()
    {
        return $this->assets()->where('asset_name', 'like', 'product-internal%');
    }

    public function external_assets()
    {
        return $this->assets()->where('asset_name', 'like', 'product-external%');
    }
}
