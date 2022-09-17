<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DiseaseFarmedType extends Pivot
{  
  public function disease()
  {
      return $this->belongsTo(\App\Models\Disease::class);
  }

  public function farmed_type()
  {
      return $this->belongsTo(\App\Models\FarmedType::class);
  }

  public function farmed_type_stages()
  {
      return $this->belongsToMany(\App\Models\FarmedType::class, 'disease_farmed_type_stage', 'disease_farmed_type_id', 'farmed_type_stage_id')->using(DiseaseFarmedTypeStage::class);
  }
}