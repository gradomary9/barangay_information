<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blotter extends Model
{
    use HasFactory;

    protected $fillable = [
        'complainant_id',
        'complainant_name',
        'respondent_id',
        'respondent_name',
        'incident_date',
        'incident_description',
        'location',
        'status',
    ];

    protected $casts = [
        'incident_date' => 'date',
    ];

    // Relationships
    public function complainant()
    {
        return $this->belongsTo(Resident::class, 'complainant_id');
    }

    public function respondent()
    {
        return $this->belongsTo(Resident::class, 'respondent_id');
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}
