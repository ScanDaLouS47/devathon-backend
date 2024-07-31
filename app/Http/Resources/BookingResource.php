<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reservationDate' => $this->reservationDate,
            'user' => new UserResource($this->whenLoaded('user')),
            'table' => new TableResource($this->whenLoaded('table')),
            'status' => new StatusResource($this->whenLoaded('status'))
        ];
    }
}
