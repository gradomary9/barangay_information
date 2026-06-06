<x-layout title="Resident Reports">
    @php($type = 'residents')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Resident Report</h4>
            <small class="text-muted">Total registered residents and household assignment.</small>
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
            <div class="col-md-4">
                <strong>Total residents</strong>
                <p class="fs-4">{{ $total }}</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <x-table.head>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Barangay</th>
                </x-table.head>
                <tbody>
                    @forelse($residents as $resident)
                        <x-table.row>
                            <td>{{ $resident->first_name }} {{ $resident->last_name }}</td>
                            <td>{{ $resident->email ?? $resident->user?->email ?? 'N/A' }}</td>
                            <td>{{ $resident->contact_number ?? 'N/A' }}</td>
                            <td>{{ $resident->household?->barangay ?? 'N/A' }}</td>
                        </x-table.row>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No residents found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-layout>
