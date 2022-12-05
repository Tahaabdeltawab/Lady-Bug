<?php

namespace App\Models;

use Eloquent as Model;

class ConsultancyProfile extends Model
{


    public $table = 'consultancy_profiles';




    public $fillable = [
        'user_id',
        'experience',
        'status',
        'ar',
        'en',
        'consultancy_price',
        'month_consultancy_price',
        'year_consultancy_price',
        'free_consultancy'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'experience' => 'integer',
        'status' => 'boolean',
        'ar' => 'boolean',
        'en' => 'boolean',
        'consultancy_price' => 'decimal:2',
        'month_consultancy_price' => 'decimal:2',
        'year_consultancy_price' => 'decimal:2',
        'free_consultancy' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'experience' => 'required',
        'work_fields' => 'nullable|array',
        'work_fields.*' => 'exists:work_fields,id',
        'ar' => 'required',
        'en' => 'required',
        'consultancy_price' => 'required',
        'month_consultancy_price' => 'required',
        'year_consultancy_price' => 'required',
        'free_consultancy' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function workFields()
    {
        return $this->belongsToMany(\App\Models\WorkField::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function offlineConsultancyPlans()
    {
        return $this->hasMany(\App\Models\OfflineConsultancyPlan::class);
    }

    /**
     * Scopes
     */

     public function scopeActive($q){
        return $q->where('status', 1);
     }
     public function scopeInactive($q){
        return $q->where('status', 0);
     }
}
