<x-layout title="Blotter Reports">
    @php($type = 'blotters')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Blotter Report</h4>
            <small class="text-muted">Incident report summary by case status.</small>
        </div>
        <div class="btn-group" role="group">
            <a href="{{ route('reports.export', ['type' => $type, 'format' => 'pdf']) }}" class="btn btn-outline-danger btn-sm">PDF</a>
            <a href="{{ route('reports.export', ['type' => $type, 'format' => 'xlsx']) }}" class="btn btn-outline-success btn-sm">XLSX</a>
            <a href="{{ route('reports.export', ['type' => $type, 'format' => 'csv']) }}" class="btn btn-outline-primary btn-sm">CSV</a>
            <a href="{{ route('reports.export', ['type' => $type, 'format' => 'json']) }}" class="btn btn-outline-secondary btn-sm">JSON</a>
        </div>
    </div>

    <x-card>
        <div class="row mb-4">
            <div class="col-md-3"><strong>Total blotters</strong><p class="fs-4">{{ $stats['total'] ?? 0 }}</p></div>
            <div class="col-md-3"><strong>Open</strong><p class="fs-5">{{ $stats['open'] ?? 0 }}</p></div>
            <div class="col-md-3"><strong>Closed</strong><p class="fs-5">{{ $stats['closed'] ?? 0 }}</p></div>
            <div class="col-md-3"><strong>Resolved</strong><p class="fs-5">{{ $stats['resolved'] ?? 0 }}</p></div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <x-table.head>
                    <th>ID</th><th>Complainant</th><th>Respondent</th><th>Date</th><th>Location</th><th>Status</th>
                </x-table.head>
                <tbody>
                    @forelse($blotters as $blotter)
                        <x-table.row>
                            <td>{{ $blotter->id }}</td>
                            <td>{{ $blotter->complainant_name ?? trim(($blotter->complainant?->first_name ?? '') . ' ' . ($blotter->complainant?->last_name ?? '')) ?: 'N/A' }}</td>
                            <td>{{ $blotter->respondent_name ?? trim(($blotter->respondent?->first_name ?? '') . ' ' . ($blotter->respondent?->last_name ?? '')) ?: 'N/A' }}</td>
                            <td>{{ $blotter->incident_date?->format('M d, Y') }}</td>
                            <td>{{ $blotter->location ?? 'N/A' }}</td>
                            <td class="text-capitalize">{{ $blotter->status }}</td>
                        </x-table.row>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No blotter records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-layout>
