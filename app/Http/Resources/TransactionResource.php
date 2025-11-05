<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                =>$this->id,
            'wallet'            =>new WalletResource($this->whenLoaded('wallet')),
            'type'              =>$this->type,
            'amount'            =>$this->amount,
            'status'            =>$this->translateStatus($this->status),
            'reference'         =>$this->reference,
        ];
    }

    private function translateStatus(string $status): string
    {
        return match ($status) {
            'pending'       => __('Pending'),
            'failed'       => __('Failed'),
            default         => __('Completed'),
        };
    }
}
