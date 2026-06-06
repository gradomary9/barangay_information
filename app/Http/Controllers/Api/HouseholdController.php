<?php

namespace App\Http\Controllers\Api;

use App\Models\Household;
use App\Http\Resources\HouseholdResource;
use Illuminate\Http\Request;

class HouseholdController
{
    public function index()
    {
        $households = Household::with(['head', 'residents'])->paginate(15);
        return HouseholdResource::collection($households);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'household_head_name' => 'nullable|string|max:255',
            'address' => 'required|string',
            'barangay' => 'required|string',
            'purok' => 'nullable|string',
        ]);

        $household = Household::create($validated);

        return new HouseholdResource($household);
    }

    public function show(Household $household)
    {
        return new HouseholdResource($household->load(['head', 'residents']));
    }

    public function update(Request $request, Household $household)
    {
        $validated = $request->validate([
            'household_head_name' => 'nullable|string|max:255',
            'address' => 'string',
            'barangay' => 'string',
            'purok' => 'nullable|string',
        ]);

        $household->update($validated);

        return new HouseholdResource($household);
    }

    public function destroy(Household $household)
    {
        $household->delete();

        return response()->json([
            'message' => 'Household deleted successfully',
        ], 200);
    }
}
