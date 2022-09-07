<?php

namespace App\Models;

use Eloquent as Model;

class Asset extends Model
{

    public $table = 'assets';



    public $fillable = [
        'asset_name',
        'asset_url',
        'asset_size',
        'asset_mime',
        'assetable_type',
        'assetable_id'
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
        'assetable_type' => 'string',
        'assetable_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'asset_name' => 'required|max:200',
        'asset_url' => 'required|max:200',
        'asset_size' => 'required',
        'asset_mime' => 'required|max:200',
        'assetable_type' => 'required|max:200',
        'assetable_id' => 'required',
    ];


    public function assetable()
    {
        return $this->morphTo();
    }


}
