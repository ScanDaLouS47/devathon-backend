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
            'tables' => $this->detailBookings->pluck('table_id')->toArray(),
            'persons' => $this->persons,
            'additional_info' => $this->additional_info,
            'allergens' => $this->allergens ? 'alergicos' : 'no alergicos',
            'shift' => $this->shift->name,
        ];
    }
}
