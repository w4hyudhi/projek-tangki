<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    { $data = [
        'id' => $this->id,
        'name' => $this->user->name,
        'photo_path' => $this->user->photo_path,
        'rating' => $this->star,
        'review' => $this->comments,
        'created_at' => $this->created_at,
    ];

    return $data;
    }
}
