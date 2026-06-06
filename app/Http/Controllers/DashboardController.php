<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Clearance;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user && strtolower($user->role ?? '') === 'admin') {
            return view('admin.dashboard', [
                'residentCount' => Resident::count(),
                'householdCount' => Household::count(),
                'blotterCount' => Blotter::count(),
                'pendingClearances' => Clearance::where('status', 'pending')->count(),
                'approvedClearances' => Clearance::where('status', 'approved')->count(),
                'rejectedClearances' => Clearance::where('status', 'rejected')->count(),
            ]);
        }

        return view('resident.dashboard');
    }
}
