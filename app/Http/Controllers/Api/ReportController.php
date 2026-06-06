<?php

namespace App\Http\Controllers\Api;

use App\Models\Resident;
use App\Models\Clearance;
use App\Models\Blotter;
use Illuminate\Http\Request;

class ReportController
{
    public function residents()
    {
        $residents = Resident::with('household')->get();
        $total = $residents->count();

        return response()->json([
            'total_residents' => $total,
            'data' => $residents,
        ], 200);
    }

    public function blotters()
    {
        $blotters = Blotter::with(['complainant', 'respondent'])->get();
        $openCount = $blotters->where('status', 'open')->count();
        $closedCount = $blotters->where('status', 'closed')->count();
        $resolvedCount = $blotters->where('status', 'resolved')->count();

        return response()->json([
            'total_blotters' => $blotters->count(),
            'open' => $openCount,
            'closed' => $closedCount,
            'resolved' => $resolvedCount,
            'data' => $blotters,
        ], 200);
    }

    public function clearances()
    {
        $clearances = Clearance::with('resident')->get();
        $pendingCount = $clearances->where('status', 'pending')->count();
        $approvedCount = $clearances->where('status', 'approved')->count();
        $rejectedCount = $clearances->where('status', 'rejected')->count();

        return response()->json([
            'total_clearances' => $clearances->count(),
            'pending' => $pendingCount,
            'approved' => $approvedCount,
            'rejected' => $rejectedCount,
            'data' => $clearances,
        ], 200);
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'json');
        $type = $request->query('type', 'residents');

        $data = match($type) {
            'residents' => $this->getResidentData(),
            'blotters' => $this->getBlotterData(),
            'clearances' => $this->getClearanceData(),
            default => [],
        };

        if ($format === 'json') {
            return response()->json($data, 200);
        }

        return response()->json([
            'message' => 'Export format not yet implemented. Use format=json',
            'supported_formats' => ['json'],
        ], 200);
    }

    private function getResidentData()
    {
        return Resident::with('household')->get()->map(fn($r) => [
            'id' => $r->id,
            'name' => $r->first_name . ' ' . $r->last_name,
            'email' => $r->user->email ?? 'N/A',
            'contact' => $r->contact_number,
            'address' => $r->address,
            'barangay' => $r->household->barangay ?? 'N/A',
        ]);
    }

    private function getBlotterData()
    {
        return Blotter::with(['complainant', 'respondent'])->get()->map(fn($b) => [
            'id' => $b->id,
            'complainant' => $b->complainant->first_name . ' ' . $b->complainant->last_name,
            'respondent' => $b->respondent->first_name . ' ' . $b->respondent->last_name,
            'incident_date' => $b->incident_date,
            'description' => $b->incident_description,
            'location' => $b->location,
            'status' => $b->status,
        ]);
    }

    private function getClearanceData()
    {
        return Clearance::with('resident')->get()->map(fn($c) => [
            'id' => $c->id,
            'resident' => $c->resident->first_name . ' ' . $c->resident->last_name,
            'purpose' => $c->purpose,
            'status' => $c->status,
            'requested_at' => $c->requested_at,
            'issued_at' => $c->issued_at,
        ]);
    }
}
