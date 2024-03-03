<?php

namespace App\Http\Resources;

use App\Models\Review;
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
                'tangki_id' => intval($this->tangki_id),
                'address_id' => intval($this->address_id),
                'user' => $this->tangki->user->name,
                'tangki' => $this->tangki->name,
                'name' => $this->tangki->user->name, // 'name' => $this->tangki->user->name,
                'username' => $this->tangki->user->username,
                'email' => $this->tangki->user->email,
                'photo_path' => $this->tangki->photo_path,
                'profile_photo_url' => $this->tangki->user->photo_path,
                'phone' => $this->tangki->user->phone,
                'status' => $this->status,
                'payment' => $this->payment,
                'description' => $this->description,
                'price' => doubleval($this->price),
                'address_type' => $this->address->name,
                'address' => $this->address->address,
                'latitude' => doubleval($this->address->latitude),
                'longitude' => doubleval($this->address->longitude),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'status_booking' => StatusBookingResource::collection($this->status_booking),

                // 'review' => ReviewResponse::collection(Review::where('transaction_id', $this->id)->get()),
                // 'review' => ReviewResponse::collection($this->review),
                'review' =>new ReviewResponse($this->review),
            ];



            return $data;
    }
}
