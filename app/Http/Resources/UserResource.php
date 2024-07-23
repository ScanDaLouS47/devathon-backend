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
        return [
            'id' => $this->id,
            'email' => $this->email,
            'status' => $this->status,
            'role' => $this->role->type,
            'personal_info' => [
                'name' => $this->personalInfo->name,
                'lName' => $this->personalInfo->lName,
                'address' => $this->personalInfo->address,
                'phone' => $this->personalInfo->phone,
                'dni' => $this->personalInfo->dni,
                'gender' => $this->personalInfo->gender,
                'age' => $this->personalInfo->age,
                'birthDate' => $this->personalInfo->birthDate
            ]
        ];
    }
}
