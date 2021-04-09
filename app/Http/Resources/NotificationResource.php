<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $is_read = $this->read_at ? true : false;
        $return = [
            'id' => $this->id,
            // 'updated_at' => $this->updated_at,
            'notifiable_id' => $this->notifiable_id,
            'type' => $this->type,
            'read_at' => $this->read_at,
            'is_read' => $is_read,
            'created_at' => $this->created_at->diffForHumans(),
            'title' => $this->data['title'],
            'body' => $this->data['body'],
        ];
        
        $data = $this->data;
        unset($data['title']);
        unset($data['body']);
        $return['data'] = $data;
        
        return $return;
    }
}
