<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
            $data = [
                'id' => $this->tangki->id,
                'user_id' => $this->id,
                'name' => $this->name,
                'username' => $this->username,
                'phone' => $this->phone,
                'email' => $this->email,
                'photo_path' => $this->photo_path,
                'store_name' => $this->tangki->name,
                'status' => $this->tangki->status,
                'active' => intval($this->tangki->active),
                'type' => $this->tangki->type,
                'price' => doubleval($this->tangki->price),
                'scedule' => $this->tangki->scedule,
                'description' => $this->tangki->description,
                'store_image' => $this->tangki->photo_path,
            ];

            return $data;
    }
}
