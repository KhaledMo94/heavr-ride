<?php

namespace App\Http\Resources;

use App\Http\Controllers\Dashboard\UserController;
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
        return [
            'id'                        => $this->id,
            'name'                      => $this->name,
            'email'                     => $this->email,
            'phone'                     => $this->phone_number,
            'image'                     => $this->image ? asset('storage/' . $this->image) : null,
            'fcm_token'                 => $this->fcm_token,
            'player_id'                 => $this->player_id,
            'is_phone_verified'         => $this->is_phone_verified,
            'created_at'                => $this->created_at,
            'status'                    => $this->status,
            'wallet'                    => new WalletResource($this->whenLoaded('wallet')),
            'crane'                     => new CraneResource($this->whenLoaded('crane')),
            'rides'                     => RideResource::collection($this->whenLoaded('rides')),
        ];
    }
}
