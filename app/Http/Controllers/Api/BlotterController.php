<?php

namespace App\Http\Controllers\Api;

use App\Models\Blotter;
use App\Http\Resources\BlotterResource;
use Illuminate\Http\Request;

class BlotterController
{
    public function index()
    {
        $blotters = Blotter::with(['complainant', 'respondent'])->paginate(15);
        return BlotterResource::collection($blotters);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'complainant_name' => 'required|string|max:255|different:respondent_name',
            'respondent_name' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_description' => 'required|string',
            'location' => 'required|string',
        ]);

        $blotter = Blotter::create([
            ...$validated,
            'status' => 'open',
        ]);

        return new BlotterResource($blotter->load(['complainant', 'respondent']));
    }

    public function show(Blotter $blotter)
    {
        return new BlotterResource($blotter->load(['complainant', 'respondent']));
    }

    public function update(Request $request, Blotter $blotter)
    {
        $validated = $request->validate([
            'complainant_name' => 'string|max:255|different:respondent_name',
            'respondent_name' => 'string|max:255',
            'incident_date' => 'date',
            'incident_description' => 'string',
            'location' => 'string',
            'status' => 'in:open,closed,resolved',
        ]);

        $blotter->update($validated);

        return new BlotterResource($blotter);
    }

    public function destroy(Blotter $blotter)
    {
        $blotter->delete();

        return response()->json([
            'message' => 'Blotter deleted successfully',
        ], 200);
    }
}
