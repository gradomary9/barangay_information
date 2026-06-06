<x-layout title="Dashboard">
    @php
        $totalRequests = max(($pendingClearances ?? 0) + ($approvedClearances ?? 0) + ($rejectedClearances ?? 0), 1);
        $pendingPercent = round((($pendingClearances ?? 0) / $totalRequests) * 100);
        $approvedPercent = round((($approvedClearances ?? 0) / $totalRequests) * 100);
        $rejectedPercent = round((($rejectedClearances ?? 0) / $totalRequests) * 100);
    @endphp

    <div class="row">
        <div class="col-md-3 mb-4">
            <x-card title="Total Residents" subtitle="Active members">
                <h3 class="text-primary">{{ $residentCount ?? 0 }}</h3>
                <small class="text-muted">Registered in system</small>
            </x-card>
        </div>
        <div class="col-md-3 mb-4">
            <x-card title="Households" subtitle="Registered homes">
                <h3 class="text-info">{{ $householdCount ?? 0 }}</h3>
                <small class="text-muted">Household records</small>
            </x-card>
        </div>
        <div class="col-md-3 mb-4">
            <x-card title="Blotter Records" subtitle="Incidents tracked">
                <h3 class="text-danger">{{ $blotterCount ?? 0 }}</h3>
                <small class="text-muted">Total incidents</small>
            </x-card>
        </div>
        <div class="col-md-3 mb-4">
            <x-card title="Pending Clearances" subtitle="Awaiting approval">
                <h3 class="text-warning">{{ $pendingClearances ?? 0 }}</h3>
                <small class="text-muted">Requests pending</small>
            </x-card>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <x-card title="Clearance Request Analytics" subtitle="Status percentage">
                <div class="mb-3">
                    <div class="d-flex justify-content-between"><span>Pending</span><strong>{{ $pendingPercent }}%</strong></div>
                    <div class="progress"><div class="progress-bar bg-warning" style="width: {{ $pendingPercent }}%"></div></div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between"><span>Approved</span><strong>{{ $approvedPercent }}%</strong></div>
                    <div class="progress"><div class="progress-bar bg-success" style="width: {{ $approvedPercent }}%"></div></div>
                </div>
                <div>
                    <div class="d-flex justify-content-between"><span>Rejected</span><strong>{{ $rejectedPercent }}%</strong></div>
                    <div class="progress"><div class="progress-bar bg-danger" style="width: {{ $rejectedPercent }}%"></div></div>
                </div>
            </x-card>
        </div>

        <div class="col-md-6 mb-4">
            <x-card title="Quick Actions" subtitle="Common admin tasks">
                <div class="d-grid gap-2">
                    <a href="{{ route('residents.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add Resident</a>
                    <a href="{{ route('households.create') }}" class="btn btn-outline-primary"><i class="bi bi-house-add"></i> Add Household</a>
                    <a href="{{ route('blotters.create') }}" class="btn btn-outline-danger"><i class="bi bi-journal-plus"></i> Add Blotter</a>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-success"><i class="bi bi-download"></i> Export Reports</a>
                </div>
            </x-card>
        </div>
    </div>
</x-layout>
