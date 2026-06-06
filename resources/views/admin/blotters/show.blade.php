<x-layout title="Blotter Details">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <x-card title="Blotter Overview">
                <div class="mb-3">
                    <strong>Complainant</strong>
                    <p>{{ $blotter->complainant_name ?? trim(($blotter->complainant?->first_name ?? '') . ' ' . ($blotter->complainant?->last_name ?? '')) ?: 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Respondent</strong>
                    <p>{{ $blotter->respondent_name ?? trim(($blotter->respondent?->first_name ?? '') . ' ' . ($blotter->respondent?->last_name ?? '')) ?: 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Incident Date</strong>
                    <p>{{ $blotter->incident_date?->format('M d, Y') }}</p>
                </div>
                <div class="mb-3">
                    <strong>Location</strong>
                    <p>{{ $blotter->location }}</p>
                </div>
                <div class="mb-3">
                    <strong>Description</strong>
                    <p>{{ $blotter->incident_description }}</p>
                </div>
                <div class="mb-3">
                    <strong>Status</strong>
                    <p class="text-capitalize">{{ $blotter->status }}</p>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('blotters.index') }}" class="btn btn-secondary">Back</a>
                    <a href="{{ route('blotters.edit', $blotter) }}" class="btn btn-primary">Edit Record</a>
                </div>
            </x-card>
        </div>
    </div>
</x-layout>
