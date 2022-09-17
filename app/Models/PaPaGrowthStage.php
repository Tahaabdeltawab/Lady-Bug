<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PaPaGrowthStage extends Pivot
{
  public function pathogen()
  {
      return $this->belongsTo(\App\Models\Pathogen::class);
  }

  public function pathogen_growth_stage()
  {
      return $this->belongsTo(\App\Models\PathogenGrowthStage::class);
  }

  public function assets()
  {
      return $this->morphMany(Asset::class, 'assetable');
  }

}