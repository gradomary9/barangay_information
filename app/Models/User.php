<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function resident()
    {
        return $this->hasOne(Resident::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    // Check if user is admin
    public function isAdmin()
    {
        return strtolower($this->role) === 'admin';
    }

    // Check if user is resident
    public function isResident()
    {
        return strtolower($this->role) === 'resident';
    }
}
