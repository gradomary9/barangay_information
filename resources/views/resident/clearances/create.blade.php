<x-layout title="Request Clearance">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Request Barangay Clearance</h4>
            <p class="text-muted mb-0">
                Fill out the purpose of your clearance request.
            </p>
        </div>

        <a href="{{ route('clearances.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <x-card title="Clearance Request Form">
        <form action="{{ route('clearances.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="purpose" class="form-label fw-bold">
                    Purpose of Request
                </label>

                <textarea name="purpose"
                          id="purpose"
                          rows="5"
                          class="form-control @error('purpose') is-invalid @enderror"
                          placeholder="Example: Job application, school requirement, local employment, postal ID, business permit..."
                          required>{{ old('purpose') }}</textarea>

                @error('purpose')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('clearances.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Submit Request
                </button>
            </div>
        </form>
    </x-card>
</x-layout>