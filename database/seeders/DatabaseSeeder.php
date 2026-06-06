<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Blotter;
use App\Models\Clearance;
use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Barangay Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $residentUser = User::updateOrCreate(
            ['email' => 'resident@gmail.com'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make('password123'),
                'role' => 'resident',
            ]
        );

        $household = Household::updateOrCreate(
            ['address' => 'Barangay Centro'],
            [
                'barangay' => 'Centro',
                'purok' => '1',
            ]
        );

        $resident = Resident::updateOrCreate(
            ['user_id' => $residentUser->id],
            [
                'household_id' => $household->id,
                'first_name' => 'Juan',
                'middle_name' => 'Dela',
                'last_name' => 'Cruz',
                'birth_date' => now()->subYears(30)->toDateString(),
                'gender' => 'male',
                'contact_number' => '09123456789',
                'address' => 'Barangay Centro',
            ]
        );

        $household->update([
            'household_head_id' => $resident->id,
        ]);

        $residentTwoUser = User::updateOrCreate(
            ['email' => 'maria@gmail.com'],
            [
                'name' => 'Maria Santos',
                'password' => Hash::make('password123'),
                'role' => 'resident',
            ]
        );

        $residentTwo = Resident::updateOrCreate(
            ['user_id' => $residentTwoUser->id],
            [
                'household_id' => $household->id,
                'first_name' => 'Maria',
                'middle_name' => null,
                'last_name' => 'Santos',
                'birth_date' => now()->subYears(26)->toDateString(),
                'gender' => 'female',
                'contact_number' => '09987654321',
                'address' => 'Barangay Centro',
            ]
        );

        Clearance::updateOrCreate(
            [
                'resident_id' => $resident->id,
                'purpose' => 'Employment requirement',
            ],
            [
                'status' => 'pending',
                'requested_at' => now()->subDays(2),
            ]
        );

        Clearance::updateOrCreate(
            [
                'resident_id' => $residentTwo->id,
                'purpose' => 'School requirement',
            ],
            [
                'status' => 'approved',
                'requested_at' => now()->subDays(5),
                'issued_at' => now()->subDays(4),
            ]
        );

        Blotter::updateOrCreate(
            [
                'complainant_name' => 'Juan Cruz',
                'respondent_name' => 'Maria Santos',
                'incident_date' => now()->subDays(1)->toDateString(),
            ],
            [
                'complainant_id' => $resident->id,
                'respondent_id' => $residentTwo->id,
                'incident_description' => 'Sample noise complaint for testing blotter records.',
                'location' => 'Barangay Centro',
                'status' => 'open',
            ]
        );

        Announcement::updateOrCreate(
            ['title' => 'Barangay Clean-up Drive'],
            [
                'description' => 'All residents are invited to join the clean-up drive this weekend.',
                'created_by' => $admin->id,
                'published_at' => now(),
            ]
        );
    }
}