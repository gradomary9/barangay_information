<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'household_id',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'contact_number',
        'address',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function clearances()
    {
        return $this->hasMany(Clearance::class);
    }

    public function complainantBlotters()
    {
        return $this->hasMany(Blotter::class, 'complainant_id');
    }

    public function respondentBlotters()
    {
        return $this->hasMany(Blotter::class, 'respondent_id');
    }

    // Get full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }
}
