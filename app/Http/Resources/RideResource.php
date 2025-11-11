<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user'          => new UserResource($this->whenLoaded('user')),
            'crane'         => new CraneResource($this->whenLoaded('crane')),

            'start_point'   => [
                'latitude'  => $this->start_latitude,
                'longitude' => $this->start_longitude,
            ],

            'end_point'     => [
                'latitude'  => $this->end_latitude,
                'longitude' => $this->end_longitude,
            ],

            'distance'      => $this->distance,
            'duration'      => $this->duration,
            'started_at'    => $this->started_at,
            'completed_at'  => $this->completed_at,
            'status'        => $this->translateStatus($this->status),
            'fare'          => $this->fare,
            'created_at'    => $this->created_at,
        ];
    }

    /**
     * Translate ride status into readable form.
     */
    private function translateStatus(?string $status): string
    {
        return match ($status) {
            'pending'       => __('Pending'),
            'accepted'      => __('Accepted'),
            'in_progress'   => __('In Progress'),
            'refused'       => __('Refused'),
            'cancelled'     => __('Cancelled'),
            default         => __('Completed'),
        };
    }
}
