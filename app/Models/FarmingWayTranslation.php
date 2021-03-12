<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmingWayTranslation extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;

    // public function farming_way(){
    //     return $this->belongsTo(FarmingWay::class);
    // }
}