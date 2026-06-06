<?php

namespace App\Http\Controllers\Api;

use App\Models\Resident;
use App\Http\Resources\ResidentResource;
use Illuminate\Http\Request;

class ResidentController
{
    public function index()
    {
        $residents = Resident::with(['user', 'household', 'clearances'])->paginate(15);
        return ResidentResource::collection($residents);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'household_id' => 'nullable|exists:households,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $resident = Resident::create($validated);

        return new ResidentResource($resident->load(['user', 'household', 'clearances']));
    }

    public function show(Resident $resident)
    {
        return new ResidentResource($resident->load(['user', 'household', 'clearances']));
    }

    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'household_id' => 'nullable|exists:households,id',
            'first_name' => 'string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'string|max:255',
            'birth_date' => 'date',
            'gender' => 'in:male,female,other',
            'contact_number' => 'string|max:20',
            'address' => 'string',
        ]);

        $resident->update($validated);

        return new ResidentResource($resident->load(['user', 'household', 'clearances']));
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();

        return response()->json([
            'message' => 'Resident deleted successfully',
        ], 200);
    }
}
