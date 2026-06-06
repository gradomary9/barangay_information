<x-layout title="Household Details">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <x-card title="Household Overview">
                <div class="mb-3">
                    <strong>Address:</strong>
                    <p>{{ $household->address }}</p>
                </div>
                <div class="mb-3">
                    <strong>Household Number:</strong>
                    <p>Household #{{ $household->id }}</p>
                </div>
                <div class="mb-3">
                    <strong>Zone:</strong>
                    <p>{{ $household->purok ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Household Head:</strong>
                    <p>{{ $household->household_head_name ?? trim(($household->head?->first_name ?? '') . ' ' . ($household->head?->last_name ?? '')) ?: 'No head assigned' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Number of Residents:</strong>
                    <p><span class="badge bg-primary">{{ $household->residents->count() }}</span></p>
                </div>
                <div class="mb-3">
                    <strong>Members:</strong>
                    <ul>
                        @forelse($household->residents as $resident)
                            <li>{{ $resident->first_name }} {{ $resident->last_name }}</li>
                        @empty
                            <li class="text-muted">No residents assigned.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('households.index') }}" class="btn btn-secondary">Back</a>
                    <a href="{{ route('households.edit', $household) }}" class="btn btn-primary">Edit Household</a>
                </div>
            </x-card>
        </div>
    </div>
</x-layout>
