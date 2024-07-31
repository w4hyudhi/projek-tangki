<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResponse extends JsonResource
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
                'user' => $this->user->name,
                'username' => $this->user->username,
                'email' => $this->user->email,
                'photo_path' => $this->user->photo_path,
                'profile_photo_url' => $this->user->profile_photo_url,
                'phone' => $this->user->phone,
                'verified' => $this->user->email_verified_at ? true : false,
                'status' => $this->status,
                'payment' => $this->payment,
                'description' => $this->description,
                'price' => $this->price,
                'address_type' => $this->address->name,
                'address' => $this->address->address,
                'latitude' => doubleval($this->address->latitude),
                'longitude' => doubleval($this->address->longitude),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                 'status_booking' => StatusBookingResource::collection($this->status_booking),
            ];



            return $data;
    }
}
