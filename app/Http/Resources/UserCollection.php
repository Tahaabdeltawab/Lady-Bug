<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{

    protected $farm;

    public function farm($farm){
        $this->farm = $farm;
        return $this;
    }


    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request){
        return $this->collection->map(function(UserResource $resource) use($request){
            return $resource->farm($this->farm)->toArray($request);
        })->all();
        // return $this->collection->map->toArray($request)->all();
    }

    // public function toArray($request)
    // {
    //     return parent::toArray($request);
    // }
}
