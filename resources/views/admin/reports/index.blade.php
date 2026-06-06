<x-layout title="Reports">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Reports</h4>
            <small class="text-muted">View and export reports as PDF, XLSX, CSV, or JSON.</small>
        </div>
    </div>

    @php
        $reportCards = [
            ['type' => 'residents', 'title' => 'Resident Reports', 'text' => 'Generate reports for all registered residents.', 'btn' => 'primary', 'route' => route('reports.residents')],
            ['type' => 'blotters', 'title' => 'Blotter Reports', 'text' => 'View incident and blotter records reports.', 'btn' => 'danger', 'route' => route('reports.blotters')],
            ['type' => 'clearances', 'title' => 'Clearance Reports', 'text' => 'Generate clearance request reports.', 'btn' => 'success', 'route' => route('reports.clearances')],
        ];
    @endphp

    <div class="row">
        @foreach($reportCards as $card)
            <div class="col-md-6 mb-4">
                <x-card title="{{ $card['title'] }}">
                    <p class="text-muted mb-3">{{ $card['text'] }}</p>

                    <a href="{{ $card['route'] }}" class="btn btn-{{ $card['btn'] }} mb-2">
                        <i class="bi bi-eye"></i> View Report
                    </a>

                    <div class="btn-group mb-2" role="group">
                        <a href="{{ route('reports.export', ['type' => $card['type'], 'format' => 'pdf']) }}" class="btn btn-outline-danger btn-sm">PDF</a>
                        <a href="{{ route('reports.export', ['type' => $card['type'], 'format' => 'xlsx']) }}" class="btn btn-outline-success btn-sm">XLSX</a>
                        <a href="{{ route('reports.export', ['type' => $card['type'], 'format' => 'csv']) }}" class="btn btn-outline-primary btn-sm">CSV</a>
                        <a href="{{ route('reports.export', ['type' => $card['type'], 'format' => 'json']) }}" class="btn btn-outline-secondary btn-sm">JSON</a>
                    </div>
                </x-card>
            </div>
        @endforeach
    </div>
</x-layout>
