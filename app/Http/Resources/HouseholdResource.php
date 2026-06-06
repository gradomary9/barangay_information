<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HouseholdResource extends JsonResource
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
            'household_head_id' => $this->household_head_id,
            'household_head_name' => $this->household_head_name ?? trim(($this->head?->first_name ?? '') . ' ' . ($this->head?->last_name ?? '')) ?: null,
            'address' => $this->address,
            'barangay' => $this->barangay,
            'purok' => $this->purok,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
