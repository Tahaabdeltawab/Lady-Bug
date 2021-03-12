<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="Asset",
 *      required={""},
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
 *          property="asset_name",
 *          description="asset_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="asset_url",
 *          description="asset_url",
 *          type="string"
 *      )
 * )
 */
class Asset extends Model
{
    use SoftDeletes;


    public $table = 'assets';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'asset_name',
        'asset_url',
        'asset_size',
        'asset_mime',
        'assetable_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'asset_name' => 'string',
        'asset_url'  => 'string',
        'asset_size' => 'string',
        'asset_mime' => 'string',
        'assetable_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'asset_name' => 'required|max:200',
        'asset_url' => 'required|max:200',
        'asset_size' => 'required|max:200',
        'asset_mime' => 'required|max:200',
        'assetable_type' => 'required|max:200'
    ];

    
}
