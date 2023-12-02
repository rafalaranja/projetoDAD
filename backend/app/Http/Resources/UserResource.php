<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->user_type,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'gender_name' => $this->genderName,
            'photo_url' => $this->photo_url,
        ];
    }
}
