<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmCollection extends JsonResource
{

    public function toArray($request)
    {
        $farm_detail['id'] = $this->id;
        $farm_detail['admin'] = $this->admin->email;
        $farm_detail['code'] = $this->code;
        $farm_detail['farm_activity_type'] = $this->farm_activity_type->name;
        $farm_detail['farmed_type'] = $this->farmed_type->name;
        return $farm_detail;
    }
}
