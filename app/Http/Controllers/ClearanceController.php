<?php

namespace App\Http\Controllers;

use App\Models\Clearance;
use App\Models\Resident;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClearanceController
{
    private function getOrCreateResidentProfile(): Resident
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You must be logged in.');
        }

        if (strtolower($user->role ?? '') !== 'resident') {
            abort(403, 'This page is only available for resident accounts.');
        }

        if ($user->resident) {
            return $user->resident;
        }

        $name = trim($user->name ?? 'Resident User');
        $parts = preg_split('/\s+/', $name);

        return Resident::create([
            'user_id' => $user->id,
            'household_id' => null,
            'first_name' => $parts[0] ?? 'Resident',
            'middle_name' => null,
            'last_name' => $parts[1] ?? 'User',
            'birth_date' => '2000-01-01',
            'gender' => 'other',
            'contact_number' => 'N/A',
            'address' => 'N/A',
        ]);
    }

    public function index(): View
    {
        $resident = $this->getOrCreateResidentProfile();

        $clearances = $resident->clearances()
            ->latest('requested_at')
            ->paginate(10);

        return view('resident.clearances.index', compact('clearances'));
    }

    public function create(): View
    {
        $this->getOrCreateResidentProfile();

        return view('resident.clearances.create');
    }

    public function show(Clearance $clearance): View
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if (strtolower($user->role ?? '') === 'admin') {
            return view('resident.clearances.show', compact('clearance'));
        }

        $resident = $this->getOrCreateResidentProfile();

        if ((int) $clearance->resident_id !== (int) $resident->id) {
            abort(403);
        }

        return view('resident.clearances.show', compact('clearance'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'purpose' => 'required|string|max:500',
        ]);

        $resident = $this->getOrCreateResidentProfile();

        $resident->clearances()->create([
            'purpose' => $validated['purpose'],
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return redirect()
            ->route('clearances.index')
            ->with('success', 'Clearance request submitted successfully!');
    }

    public function adminIndex(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $clearances = Clearance::with('resident')
            ->when($search, function ($query) use ($search) {
                $query->where('purpose', 'like', "%{$search}%")
                    ->orWhereHas('resident', function ($residentQuery) use ($search) {
                        $residentQuery
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('requested_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clearances.index', compact('clearances', 'search', 'status'));
    }

    public function approve(Clearance $clearance): RedirectResponse
    {
        $clearance->update([
            'status' => 'approved',
            'issued_at' => now(),
        ]);

        return back()->with('success', 'Clearance approved!');
    }

    public function reject(Clearance $clearance): RedirectResponse
    {
        $clearance->update([
            'status' => 'rejected',
            'issued_at' => null,
        ]);

        return back()->with('success', 'Clearance rejected!');
    }
}