<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class User extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $url = env('APP_URL').'/'. 'public/';
        return [
            "name"      =>  $this->name,
            "email"     => $this->email,
            "password"  => $this->password,
            "phone"     => $this->phone,
            "profile_image"=> $url . $this->profile_image
        ];

    }
}
