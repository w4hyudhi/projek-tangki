<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TangkiResponse extends JsonResource
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
                'name' => $this->name,
                'type' => $this->type,
                'price' => $this->price,
                'status' => $this->status,
                'scedule' => $this->scedule,
                'description' => $this->description,
                'photo_path' => $this->photo_path,
                'user' => UserResponse::make($this->user),
                'review' => ReviewResponse::collection($this->review),
                'transaction' => TransactionHistoryResponse::collection($this->transaction),




            ];

            return $data;
    }
}
