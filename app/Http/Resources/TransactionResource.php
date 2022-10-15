<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'type' => $this->type,
            'user_id' => $this->user_id,
            'gateway' => $this->gateway,
            'total' => $this->total,
            'description' => $this->description,
            'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
