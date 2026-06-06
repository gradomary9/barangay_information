<x-layout title="Blotters">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Blotter Management</h4>
            <small class="text-muted">Track incidents, complainants, respondents, and case status.</small>
        </div>

        <a href="{{ route('blotters.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Blotter
        </a>
    </div>

    <x-card>
        <form method="GET" action="{{ route('blotters.index') }}" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                       placeholder="Search resident, location, or incident description...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All status</option>
                    <option value="open" @selected(($status ?? '') === 'open')>Open</option>
                    <option value="resolved" @selected(($status ?? '') === 'resolved')>Resolved</option>
                    <option value="closed" @selected(($status ?? '') === 'closed')>Closed</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('blotters.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <x-table.head>
                    <th>Complainant</th>
                    <th>Respondent</th>
                    <th>Incident Date</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </x-table.head>

                <tbody>
                    @forelse($blotters as $blotter)
                        <x-table.row>
                            <td>{{ $blotter->complainant_name ?? ($blotter->complainant ? $blotter->complainant->first_name . ' ' . $blotter->complainant->last_name : 'N/A') }}</td>
                            <td>{{ $blotter->respondent_name ?? ($blotter->respondent ? $blotter->respondent->first_name . ' ' . $blotter->respondent->last_name : 'N/A') }}</td>
                            <td>{{ $blotter->incident_date ? $blotter->incident_date->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $blotter->location ?? 'N/A' }}</td>
                            <td>
                                @if($blotter->status === 'open')
                                    <span class="badge bg-warning text-dark">Open</span>
                                @elseif($blotter->status === 'resolved')
                                    <span class="badge bg-success">Resolved</span>
                                @elseif($blotter->status === 'closed')
                                    <span class="badge bg-secondary">Closed</span>
                                @else
                                    <span class="badge bg-light text-dark">{{ $blotter->status ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('blotters.show', $blotter) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('blotters.edit', $blotter) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('blotters.destroy', $blotter) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this blotter record?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </x-table.row>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No blotter records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="d-flex justify-content-center">
        {{ $blotters->links() }}
    </div>
</x-layout>
