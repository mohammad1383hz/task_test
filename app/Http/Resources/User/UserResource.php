<?php

namespace App\Http\Resources\User;


use Illuminate\Http\Resources\Json\JsonResource;

class  UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified' => $this->email_verified_at ? true : false,
            



        ];
    }
}
