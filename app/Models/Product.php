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
        'seller_id',
        'description',
        'name',
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
