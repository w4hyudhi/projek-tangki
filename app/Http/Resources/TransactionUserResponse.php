<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionUserResponse extends JsonResource
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
                'id' => $this->id,
                'user' => $this->tangki->user->name,
                'tangki' => $this->tangki->name,
                'username' => $this->tangki->user->username,
                'email' => $this->tangki->user->email,
                'photo_path' => $this->tangki->user->photo_path,
                'profile_photo_url' => $this->tangki->user->profile_photo_url,
                'phone' => $this->user->phone,
                'status' => $this->status,
                'payment' => $this->payment,
                'description' => $this->description,
                'price' => $this->price,
                'address_type' => $this->address->name,
                'address' => $this->address->address,
                'latitude' => $this->address->latitude,
                'longitude' => $this->address->longitude,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                 'status_booking' => StatusBookingResource::collection($this->status_booking),
            ];



            return $data;
    }
}
