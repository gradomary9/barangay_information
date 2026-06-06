<x-layout title="Clearance Requests">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Clearance Requests</h4>
            <small class="text-muted">Approve or reject resident clearance requests.</small>
        </div>

        <a href="{{ route('reports.index') }}" class="btn btn-secondary">View Reports</a>
    </div>

    <x-card>
        <form method="GET" action="{{ route('clearances.admin') }}" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                       placeholder="Search resident or purpose...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All status</option>
                    <option value="pending" @selected(($status ?? '') === 'pending')>Pending</option>
                    <option value="approved" @selected(($status ?? '') === 'approved')>Approved</option>
                    <option value="rejected" @selected(($status ?? '') === 'rejected')>Rejected</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('clearances.admin') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <x-table.head>
                    <th>Resident</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th>Actions</th>
                </x-table.head>

                <tbody>
                    @forelse($clearances as $clearance)
                        <x-table.row>
                            <td>{{ $clearance->resident ? $clearance->resident->first_name . ' ' . $clearance->resident->last_name : 'N/A' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($clearance->purpose ?? 'N/A', 60) }}</td>
                            <td>
                                @if($clearance->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($clearance->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($clearance->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">{{ $clearance->status ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td>{{ $clearance->requested_at ? $clearance->requested_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($clearance->status === 'pending')
                                    <form action="{{ route('clearances.approve', $clearance) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this clearance request?')">Approve</button>
                                    </form>
                                    <form action="{{ route('clearances.reject', $clearance) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this clearance request?')">Reject</button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary text-capitalize">{{ $clearance->status }}</span>
                                @endif
                            </td>
                        </x-table.row>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No clearance requests available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="d-flex justify-content-center">
        {{ $clearances->links() }}
    </div>
</x-layout>
