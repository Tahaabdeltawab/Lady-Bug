<?php

namespace App\Models;

use Eloquent as Model;



class FarmedTypeClass extends Model
{
    use \App\Traits\SpatieHasTranslations;
    public $translatable = ['name'];
	public $timestamps = false;


    public $table = 'farmed_type_classes';



    public $fillable = [
        'name',
        'farmed_type_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'farmed_type_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name.ar' => 'required|max:30',
        'name.en' => 'required|max:30',
        'farmed_type_id' => 'required'
    ];

    public function farmed_type(){
        return $this->belongsTo(FarmedType::class);
    }


}
