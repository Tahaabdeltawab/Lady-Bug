<?php

namespace App\Models;

use Eloquent as Model;
use willvincent\Rateable\Rateable;



class Product extends Model
{
    use \App\Traits\SpatieHasTranslations, Rateable;

    public $translatable = ['name', 'description'];

    public $table = 'products';



    public $fillable = [
        'price',
        'description',
        'seller_id',
        'business_id',
        'product_type_id',
        'insecticide_id',
        'fertilizer_id',
        'name',
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
        'business_id' => 'integer',
        'product_type_id' => 'integer',
        'insecticide_id' => 'integer',
        'fertilizer_id' => 'integer',
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
        'product_type_id'               => 'required|exists:product_types,id',
        'price'                         => 'required',
        'name'                          => 'required|max:200',
        'description'                   => 'required',
        'farmed_types'                  => 'nullable|array',
        'farmed_types.*'                => 'required|exists:farmed_types,id',
        'city_id'                       => 'required|exists:cities,id',
        'district_id'                   => 'required|exists:districts,id',
        'seller_mobile'                 => 'required|max:20',
        'other_links'                   => 'nullable',
        'internal_assets'               => ['nullable','array'],
        'external_assets'               => ['nullable','array'],
        'internal_assets.*'             => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg'],
        'external_assets.*'             => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg']
    ];

    protected static function booted()
    {
        static::addGlobalScope('latest', function ($q) {
            $q->latest();
        });
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function productType()
    {
        return $this->belongsTo(productType::class);
    }
    public function insecticide()
    {
        return $this->belongsTo(Insecticide::class);
    }
    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class);
    }


    public function district()
    {
        return $this->belongsTo(District::class);
    }


    public function farmedTypes()
    {
        return $this->belongsToMany(FarmedType::class);
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

    public function shippingCities()
    {
        return $this->belongsToMany(City::class, 'product_shipping_cities')->withPivot('shipping_days', 'shipping_fees');
    }

    public function ads()
    {
        return $this->hasMany(ProductAd::class);
    }
}
