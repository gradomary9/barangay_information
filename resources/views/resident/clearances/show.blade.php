<x-layout title="Clearance Details">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Clearance Request Details</h4>
            <p class="text-muted mb-0">
                View the status and details of your clearance request.
            </p>
        </div>

        <a href="{{ route('clearances.index') }}" class="btn btn-secondary">
            Back to List
        </a>
    </div>

    <x-card title="Request Information">
        <div class="mb-4">
            <small class="text-muted d-block fw-bold text-uppercase mb-2">
                Status
            </small>

            @if($clearance->status === 'pending')
                <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                    Pending
                </span>
            @elseif($clearance->status === 'approved')
                <span class="badge bg-success px-3 py-2 fs-6">
                    Approved
                </span>
            @elseif($clearance->status === 'rejected')
                <span class="badge bg-danger px-3 py-2 fs-6">
                    Rejected
                </span>
            @else
                <span class="badge bg-secondary px-3 py-2 fs-6">
                    {{ $clearance->status ?? 'N/A' }}
                </span>
            @endif
        </div>

        <div class="mb-4">
            <strong class="d-block text-secondary mb-2">
                Purpose:
            </strong>

            <p class="bg-light p-3 rounded border fs-5 mb-0">
                {{ $clearance->purpose }}
            </p>
        </div>

        <div class="row text-center bg-light m-0 p-3 rounded border">
            <div class="col-sm-6 border-end">
                <strong class="text-muted d-block">
                    Date Requested:
                </strong>

                <span>
                    {{ $clearance->requested_at
                        ? \Carbon\Carbon::parse($clearance->requested_at)->format('M d, Y - h:i A')
                        : 'N/A' }}
                </span>
            </div>

            <div class="col-sm-6">
                <strong class="text-muted d-block">
                    Date Issued/Actioned:
                </strong>

                <span>
                    {{ $clearance->issued_at
                        ? \Carbon\Carbon::parse($clearance->issued_at)->format('M d, Y - h:i A')
                        : 'N/A' }}
                </span>
            </div>
        </div>
    </x-card>
</x-layout>