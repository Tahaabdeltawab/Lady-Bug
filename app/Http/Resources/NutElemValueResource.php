<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NutElemValueResource extends JsonResource
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
            'n' => $this->n,
            'p' => $this->p,
            'k' => $this->k,
            'fe' => $this->fe,
            'b' => $this->b,
            'ca' => $this->ca,
            'mg' => $this->mg,
            's' => $this->s,
            'zn' => $this->zn,
            'mn' => $this->mn,
            'cu' => $this->cu,
            'cl' => $this->cl,
            'mo' => $this->mo,

        ];
    }
}
