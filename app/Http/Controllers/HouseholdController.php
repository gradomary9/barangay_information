<?php

namespace App\Http\Controllers;

use App\Models\Household;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HouseholdController
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $households = Household::with(['head', 'residents'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('address', 'like', "%{$search}%")
                        ->orWhere('barangay', 'like', "%{$search}%")
                        ->orWhere('purok', 'like', "%{$search}%")
                        ->orWhere('household_head_name', 'like', "%{$search}%")
                        ->orWhereHas('head', fn ($headQuery) => $headQuery
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.households.index', compact('households', 'search'));
    }

    public function create(): View
    {
        return view('admin.households.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'household_head_name' => 'nullable|string|max:255',
            'address' => 'required|string',
            'barangay' => 'required|string|max:255',
            'purok' => 'nullable|string|max:255',
        ]);

        Household::create($validated);
        return redirect()->route('households.index')->with('success', 'Household created successfully!');
    }

    public function show(Household $household): View
    {
        $household->load(['head', 'residents.user']);
        return view('admin.households.show', compact('household'));
    }

    public function edit(Household $household): View
    {
        return view('admin.households.edit', compact('household'));
    }

    public function update(Request $request, Household $household): RedirectResponse
    {
        $validated = $request->validate([
            'household_head_name' => 'nullable|string|max:255',
            'address' => 'required|string',
            'barangay' => 'required|string|max:255',
            'purok' => 'nullable|string|max:255',
        ]);

        $household->update($validated);
        return redirect()->route('households.index')->with('success', 'Household updated successfully!');
    }

    public function destroy(Household $household): RedirectResponse
    {
        $household->delete();
        return redirect()->route('households.index')->with('success', 'Household deleted successfully!');
    }
}
