<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CraneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    =>$this->id,
            'owner'                 =>new UserResource($this->whenLoaded('user')),
            'type'                  =>$this->type,
            'image'                 =>$this->image_url,
            'capacity'              =>$this->capacity,
            'license_plate'         =>$this->license_plate,
            'status'                =>$this->status,
            'is_online'             =>$this->is_online,
            'ratings_count'         =>$this->ratings_count,
            'avg_rating'            =>$this->avg_rating,
        ];
    }
}
