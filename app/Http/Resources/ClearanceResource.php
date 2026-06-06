<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClearanceResource extends JsonResource
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
            'resident_id' => $this->resident_id,
            'purpose' => $this->purpose,
            'status' => $this->status,
            'requested_at' => $this->requested_at,
            'issued_at' => $this->issued_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'resident' => [
                'id' => $this->resident?->id,
                'full_name' => $this->resident?->getFullNameAttribute(),
            ],
        ];
    }
}
