<x-layout title="Clearance Reports">
    @php($type = 'clearances')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Clearance Report</h4>
            <small class="text-muted">Clearance request summary by status.</small>
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
            <div class="col-md-3"><strong>Total clearances</strong><p class="fs-4">{{ $stats['total'] ?? 0 }}</p></div>
            <div class="col-md-3"><strong>Pending</strong><p class="fs-5">{{ $stats['pending'] ?? 0 }}</p></div>
            <div class="col-md-3"><strong>Approved</strong><p class="fs-5">{{ $stats['approved'] ?? 0 }}</p></div>
            <div class="col-md-3"><strong>Rejected</strong><p class="fs-5">{{ $stats['rejected'] ?? 0 }}</p></div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <x-table.head>
                    <th>Resident</th><th>Purpose</th><th>Status</th><th>Requested</th><th>Issued</th>
                </x-table.head>
                <tbody>
                    @forelse($clearances as $clearance)
                        <x-table.row>
                            <td>{{ $clearance->resident?->first_name }} {{ $clearance->resident?->last_name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($clearance->purpose ?? 'N/A', 60) }}</td>
                            <td class="text-capitalize">{{ $clearance->status }}</td>
                            <td>{{ $clearance->requested_at?->format('M d, Y') }}</td>
                            <td>{{ $clearance->issued_at?->format('M d, Y') ?? 'N/A' }}</td>
                        </x-table.row>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No clearance records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-layout>
