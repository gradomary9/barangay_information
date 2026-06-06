<?php

namespace App\Http\Controllers;

use App\Exports\GenericReportExport;
use App\Models\Resident;
use App\Models\Clearance;
use App\Models\Blotter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController
{
    public function index(): View
    {
        return view('admin.reports.index');
    }

    public function residents(): View
    {
        $residents = Resident::with(['household', 'user'])->latest()->get();
        $total = $residents->count();

        return view('admin.reports.residents', compact('residents', 'total'));
    }

    public function blotters(): View
    {
        $blotters = Blotter::with(['complainant', 'respondent'])->latest('incident_date')->get();
        $stats = [
            'total' => $blotters->count(),
            'open' => $blotters->where('status', 'open')->count(),
            'closed' => $blotters->where('status', 'closed')->count(),
            'resolved' => $blotters->where('status', 'resolved')->count(),
        ];

        return view('admin.reports.blotters', compact('blotters', 'stats'));
    }

    public function clearances(): View
    {
        $clearances = Clearance::with('resident')->latest('requested_at')->get();
        $stats = [
            'total' => $clearances->count(),
            'pending' => $clearances->where('status', 'pending')->count(),
            'approved' => $clearances->where('status', 'approved')->count(),
            'rejected' => $clearances->where('status', 'rejected')->count(),
        ];

        return view('admin.reports.clearances', compact('clearances', 'stats'));
    }

    public function export(Request $request)
    {
        $format = strtolower($request->query('format', 'json'));
        $type = strtolower($request->query('type', 'residents'));

        [$rows, $headings, $keys, $title] = $this->reportPayload($type);
        $filename = Str::slug($title) . '-' . now()->format('Y-m-d-His');

        return match ($format) {
            'json' => response()->json($rows)->header('Content-Disposition', "attachment; filename={$filename}.json"),
            'csv' => $this->downloadCsv($rows, $headings, $keys, "{$filename}.csv"),
            'xlsx' => Excel::download(new GenericReportExport($rows, $headings, $keys), "{$filename}.xlsx"),
            'pdf' => Pdf::loadView('admin.reports.pdf.generic', [
                    'title' => $title,
                    'rows' => $rows,
                    'headings' => $headings,
                    'keys' => $keys,
                    'generatedAt' => now(),
                ])->download("{$filename}.pdf"),
            default => back()->with('info', 'Invalid export format. Use pdf, xlsx, csv, or json.'),
        };
    }

    private function reportPayload(string $type): array
    {
        return match ($type) {
            'blotters' => [
                $this->getBlotterData(),
                ['ID', 'Complainant', 'Respondent', 'Incident Date', 'Location', 'Status'],
                ['id', 'complainant', 'respondent', 'incident_date', 'location', 'status'],
                'Blotter Report',
            ],
            'clearances' => [
                $this->getClearanceData(),
                ['ID', 'Resident', 'Purpose', 'Status', 'Requested At', 'Issued At'],
                ['id', 'resident', 'purpose', 'status', 'requested_at', 'issued_at'],
                'Clearance Report',
            ],
            default => [
                $this->getResidentData(),
                ['ID', 'Name', 'Email', 'Contact', 'Address', 'Barangay'],
                ['id', 'name', 'email', 'contact', 'address', 'barangay'],
                'Resident Report',
            ],
        };
    }

    private function downloadCsv(Collection $rows, array $headings, array $keys, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($rows, $headings, $keys) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headings);

            foreach ($rows as $row) {
                fputcsv($handle, collect($keys)->map(fn ($key) => data_get($row, $key, ''))->toArray());
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function getResidentData(): Collection
    {
        return Resident::with(['household', 'user'])->latest()->get()->map(fn ($r) => [
            'id' => $r->id,
            'name' => trim("{$r->first_name} {$r->last_name}"),
            'email' => $r->email ?? $r->user?->email ?? 'N/A',
            'contact' => $r->contact_number ?? 'N/A',
            'address' => $r->address ?? 'N/A',
            'barangay' => $r->household?->barangay ?? 'N/A',
        ]);
    }

    private function getBlotterData(): Collection
    {
        return Blotter::with(['complainant', 'respondent'])->latest('incident_date')->get()->map(fn ($b) => [
            'id' => $b->id,
            'complainant' => $b->complainant_name ?? ($b->complainant ? trim("{$b->complainant->first_name} {$b->complainant->last_name}") : 'N/A'),
            'respondent' => $b->respondent_name ?? ($b->respondent ? trim("{$b->respondent->first_name} {$b->respondent->last_name}") : 'N/A'),
            'incident_date' => optional($b->incident_date)->format('Y-m-d') ?? 'N/A',
            'description' => $b->incident_description ?? 'N/A',
            'location' => $b->location ?? 'N/A',
            'status' => $b->status ?? 'N/A',
        ]);
    }

    private function getClearanceData(): Collection
    {
        return Clearance::with('resident')->latest('requested_at')->get()->map(fn ($c) => [
            'id' => $c->id,
            'resident' => $c->resident ? trim("{$c->resident->first_name} {$c->resident->last_name}") : 'N/A',
            'purpose' => $c->purpose ?? 'N/A',
            'status' => $c->status ?? 'N/A',
            'requested_at' => optional($c->requested_at)->format('Y-m-d H:i') ?? 'N/A',
            'issued_at' => optional($c->issued_at)->format('Y-m-d H:i') ?? 'N/A',
        ]);
    }
}
