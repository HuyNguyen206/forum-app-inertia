<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>e
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
                'name' => $this->name,
                'profile_photo_url' => $this->profile_photo_url,
                'email' => $this->when($this->id === $request->user()?->id, $this->email)
            ];
    }
}
