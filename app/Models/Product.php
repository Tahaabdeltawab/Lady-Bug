<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


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
    use SoftDeletes, Translatable;

    public $translatedAttributes = ['name', 'description'];

    public $table = 'products';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'price',
        'seller_id',
        // 'description',
        // 'name',
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
    public static $rules = [
        'price' => 'required',
        'seller_id' => 'required',
        'description_ar_localized' => 'required',
        'description_en_localized' => 'required',
        'name_ar_localized' => 'required|max:200',
        'name_en_localized' => 'required|max:200',
        'city_id' => 'required',
        'district_id' => 'required',
        'seller_mobile' => 'required|max:20',
        'sold' => 'required',
        'internal_assets' => ['nullable','array'],
        'external_assets' => ['nullable','array'],
        'internal_assets.*' => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg'],
        'external_assets.*' => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg']
    ];

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
