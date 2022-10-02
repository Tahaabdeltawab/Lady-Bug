<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class ProductAd
 * @package App\Models
 * @version September 18, 2022, 1:04 pm EET
 *
 * @property \App\Models\Product $product
 * @property integer $product_id
 * @property string $name
 * @property string $description
 * @property boolean $stacked
 */
class ProductAd extends Model
{


    public $table = 'product_ads';
	public $timestamps = false;




    public $fillable = [
        'product_id',
        'name',
        'description',
        'stacked'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'stacked' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required',
        'name' => 'nullable',
        'description' => 'nullable',
        'stacked' => 'nullable',
        'asset' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function asset()
    {
        return $this->morphOne(Asset::class, 'assetable');
    }
}
