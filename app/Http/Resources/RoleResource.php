<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //SPATIE
        // return [
        //     'id'                => $this->id,
        //     'name'              => $this->name,
        //     'title'             => $this->title,
        //     'guard_name'        => $this->guard_name,
        //     'description'       => $this->description,
        //     'created_at'        => $this->created_at,
        //     'updated_at'        => $this->updated_at,
        // ];

        //LARATRUST
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'display_name'      => $this->display_name,
            'description'       => $this->description,
            // 'created_at'        => $this->created_at,
            // 'updated_at'        => $this->updated_at,
        ];
    }
}
