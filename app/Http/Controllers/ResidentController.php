<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\User;
use App\Models\Household;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ResidentController
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $gender = $request->query('gender');

        $residents = Resident::with(['user', 'household'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('household', function ($householdQuery) use ($search) {
                            $householdQuery->where('address', 'like', "%{$search}%")
                                ->orWhere('purok', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('email', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($gender, fn ($query) => $query->where('gender', $gender))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.residents.index', compact('residents', 'search', 'gender'));
    }

    public function create(): View
    {
        $users = User::whereDoesntHave('resident')
            ->orderBy('name')
            ->get();

        $households = Household::orderBy('id')->get();

        return view('admin.residents.create', compact('users', 'households'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email|unique:residents,email',
            'password' => 'required|string|min:8',

            'household_id' => 'nullable|exists:households,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'contact_number' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['user_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'resident',
            ]);

            Resident::create([
                'user_id' => $user->id,
                'email' => $validated['email'],
                'household_id' => $validated['household_id'] ?? null,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'contact_number' => $validated['contact_number'],
                'address' => Household::find($validated['household_id'] ?? null)?->purok ?? 'Unassigned',
            ]);
        });

        return redirect()->route('residents.index')->with('success', 'Resident account created successfully!');
    }

    public function show(Resident $resident): View
    {
        $resident->load(['user', 'household', 'clearances', 'complainantBlotters', 'respondentBlotters']);

        return view('admin.residents.show', compact('resident'));
    }

    public function edit(Resident $resident): View
    {
        $users = User::whereDoesntHave('resident')
            ->orWhere('id', $resident->user_id)
            ->orderBy('name')
            ->get();

        $households = Household::orderBy('id')->get();

        return view('admin.residents.edit', compact('resident', 'users', 'households'));
    }

    public function update(Request $request, Resident $resident): RedirectResponse
    {
        $userId = $resident->user_id;

        $validated = $request->validate([
            'user_name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
                Rule::unique('residents', 'email')->ignore($resident->id),
            ],

            'password' => 'nullable|string|min:8',

            'household_id' => 'nullable|exists:households,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'contact_number' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($validated, $resident) {
            $user = $resident->user;

            if ($user) {
                $userData = [
                    'name' => $validated['user_name'],
                    'email' => $validated['email'],
                    'role' => 'resident',
                ];

                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $user->update($userData);
            } else {
                $user = User::create([
                    'name' => $validated['user_name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password'] ?? 'password123'),
                    'role' => 'resident',
                ]);
            }

            $resident->update([
                'user_id' => $user->id,
                'email' => $validated['email'],
                'household_id' => $validated['household_id'] ?? null,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'contact_number' => $validated['contact_number'],
                'address' => Household::find($validated['household_id'] ?? null)?->purok ?? 'Unassigned',
            ]);
        });

        return redirect()->route('residents.index')->with('success', 'Resident updated successfully!');
    }

    public function destroy(Resident $resident): RedirectResponse
    {
        $resident->delete();

        return redirect()->route('residents.index')->with('success', 'Resident deleted successfully!');
    }
}