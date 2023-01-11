<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmXsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $farm_detail['id'] = $this->id;
        $farm_detail['code'] = $this->code;
        $farm_detail['real'] = $this->real ? 'حقيقية' : 'افتراضية';
        $farm_detail['farmed_type_name'] = @$this->farmed_type->name;
        $farm_detail['farmed_type_photo'] = @$this->farmed_type->asset->asset_url;
        $farm_detail['farming_date'] = date('Y-m-d', strtotime($this->farming_date));
        $farm_detail['area'] = $this->area;
        $farm_detail['area_unit'] = @$this->area_unit->name;
        $farm_detail['farmed_number'] = $this->farmed_number;
        $farm_detail['chemical_fertilizer_sources'] = $this->chemical_fertilizer_sources()->pluck('com_name');
        $farm_detail['seedling_sources'] =  $this->seedling_sources()->pluck('com_name');
        // for search farms as here farms may be accessed from outside their businesses so should be protected
        $farm_detail['canBeSeen'] =  $this->business->userCan('show-farms');

        return $farm_detail;
    }
}
