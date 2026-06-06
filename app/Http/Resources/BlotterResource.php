<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlotterResource extends JsonResource
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
            'case_number' => $this->case_number,
            'complainant_id' => $this->complainant_id,
            'complainant_name' => $this->complainant_name ?? trim(($this->complainant?->first_name ?? '') . ' ' . ($this->complainant?->last_name ?? '')) ?: null,
            'respondent_id' => $this->respondent_id,
            'respondent_name' => $this->respondent_name ?? trim(($this->respondent?->first_name ?? '') . ' ' . ($this->respondent?->last_name ?? '')) ?: null,
            'incident_date' => $this->incident_date,
            'incident_description' => $this->incident_description,
            'location' => $this->location,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'complainant' => [
                'id' => $this->complainant?->id,
                'full_name' => $this->complainant?->getFullNameAttribute(),
            ],
            'respondent' => [
                'id' => $this->respondent?->id,
                'full_name' => $this->respondent?->getFullNameAttribute(),
            ],
        ];
    }
}
