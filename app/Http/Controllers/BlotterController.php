<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BlotterController
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $blotters = Blotter::with(['complainant', 'respondent'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('incident_description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('complainant_name', 'like', "%{$search}%")
                        ->orWhere('respondent_name', 'like', "%{$search}%")
                        ->orWhereHas('complainant', fn ($residentQuery) => $residentQuery
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%"))
                        ->orWhereHas('respondent', fn ($residentQuery) => $residentQuery
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('incident_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.blotters.index', compact('blotters', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.blotters.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'complainant_name' => 'required|string|max:255|different:respondent_name',
            'respondent_name' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_description' => 'required|string',
            'location' => 'required|string',
        ]);

        Blotter::create($validated);
        return redirect()->route('blotters.index')->with('success', 'Blotter record created successfully!');
    }

    public function show(Blotter $blotter): View
    {
        $blotter->load(['complainant', 'respondent']);
        return view('admin.blotters.show', compact('blotter'));
    }

    public function edit(Blotter $blotter): View
    {
        return view('admin.blotters.edit', compact('blotter'));
    }

    public function update(Request $request, Blotter $blotter): RedirectResponse
    {
        $validated = $request->validate([
            'complainant_name' => 'required|string|max:255|different:respondent_name',
            'respondent_name' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_description' => 'required|string',
            'location' => 'required|string',
            'status' => 'required|in:open,closed,resolved',
        ]);

        $blotter->update($validated);
        return redirect()->route('blotters.index')->with('success', 'Blotter updated successfully!');
    }

    public function destroy(Blotter $blotter): RedirectResponse
    {
        $blotter->delete();
        return redirect()->route('blotters.index')->with('success', 'Blotter deleted successfully!');
    }
}
