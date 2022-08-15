<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class FarmedTypeClass extends Model implements TranslatableContract
{
    use /*SoftDeletes,*/ Translatable;

    public $translatedAttributes = ['name'];


    public $table = 'farmed_type_classes';


    protected $dates = ['deleted_at'];



    public $fillable = [
        // 'name',
        'farmed_type_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'name' => 'string',
        'farmed_type_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_ar_localized' => 'required|max:200',
        'name_en_localized' => 'required|max:200',
        'farmed_type_id' => 'required'
    ];

    public function farmed_type(){
        return $this->belongsTo(FarmedType::class);
    }


}
