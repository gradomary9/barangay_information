<?php

namespace App\Http\Controllers\Api;

use App\Models\Clearance;
use App\Http\Resources\ClearanceResource;
use Illuminate\Http\Request;

class ClearanceController
{
    public function index()
    {
        $clearances = Clearance::with('resident')->paginate(15);
        return ClearanceResource::collection($clearances);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'purpose' => 'required|string',
        ]);

        $clearance = Clearance::create([
            'resident_id' => $validated['resident_id'],
            'purpose' => $validated['purpose'],
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return new ClearanceResource($clearance->load('resident'));
    }

    public function show(Clearance $clearance)
    {
        return new ClearanceResource($clearance->load('resident'));
    }

    public function update(Request $request, Clearance $clearance)
    {
        $validated = $request->validate([
            'purpose' => 'string',
        ]);

        $clearance->update($validated);

        return new ClearanceResource($clearance);
    }

    public function destroy(Clearance $clearance)
    {
        $clearance->delete();

        return response()->json([
            'message' => 'Clearance deleted successfully',
        ], 200);
    }

    public function approve(Clearance $clearance)
    {
        $clearance->update([
            'status' => 'approved',
            'issued_at' => now(),
        ]);

        return new ClearanceResource($clearance);
    }

    public function reject(Clearance $clearance)
    {
        $clearance->update(['status' => 'rejected']);

        return new ClearanceResource($clearance);
    }
}
