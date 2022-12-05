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
        $farm_detail['ladybug_rating'] = !is_null($this->ladybug_rating) ? ceil($this->ladybug_rating * 100 / 5).'%' : null;
        $farm_detail['farm_activity_type'] = $this->farm_activity_type->name;
        $farm_detail['farmed_type'] = $this->farmed_type->name;
        return $farm_detail;
    }
}
