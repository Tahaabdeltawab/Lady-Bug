<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DiseaseFarmedTypeStage extends Pivot
{
  public function disease_farmed_type()
  {
      return $this->belongsTo(\App\Models\DiseaseFarmedType::class);
  }

  public function farmed_type_stage()
  {
      return $this->belongsTo(\App\Models\FarmedTypeStage::class);
  }

  public function assets()
  {
      return $this->morphMany(Asset::class, 'assetable');
  }

}