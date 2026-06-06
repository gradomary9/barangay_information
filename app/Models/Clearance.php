<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'purpose',
        'status',
        'requested_at',
        'issued_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'issued_at' => 'datetime',
    ];

    // Relationships
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
