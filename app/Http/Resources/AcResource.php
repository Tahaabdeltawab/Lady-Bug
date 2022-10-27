<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AcResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'who_class' => $this->who_class,
            'who_class' => @app('\App\Http\Controllers\API\AcAPIController')->who_classes($this->who_class),
            'withdrawal_days' => $this->withdrawal_days,
            'precautions' => $this->precautions
        ];
    }
}
