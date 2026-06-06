<x-layout title="Resident Details">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <x-card title="Resident Information">
                <div class="mb-3">
                    <strong>Name:</strong>
                    <p>{{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }}</p>
                </div>
                <div class="mb-3">
                    <strong>Email:</strong>
                    <p>{{ $resident->email ?? $resident->user?->email ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Contact:</strong>
                    <p>{{ $resident->contact_number }}</p>
                </div>
                <div class="mb-3">
                    <strong>Household:</strong>
                    <p>{{ $resident->household?->address ?? 'Unassigned' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Address:</strong>
                    <p>{{ $resident->address }}</p>
                </div>
                <div class="mb-3">
                    <strong>Birth Date:</strong>
                    <p>{{ $resident->birth_date?->format('M d, Y') }}</p>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('residents.index') }}" class="btn btn-secondary">Back</a>
                    <a href="{{ route('residents.edit', $resident) }}" class="btn btn-primary">Edit Resident</a>
                </div>
            </x-card>
        </div>
    </div>
</x-layout>
