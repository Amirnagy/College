<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $url = env('APP_URL') .'/'.'public/' ;
        return
        [
            "name"      =>  $this->name,
            "email"     => $this->email,
            "phone"     => $this->phone,
            "admin" => $this->admin,
            "email_verified_at" => $this->email_verified_at,
            "profile_image"=> $url . $this->profile_image
        ];
    }



}
