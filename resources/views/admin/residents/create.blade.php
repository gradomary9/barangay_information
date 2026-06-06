<x-layout title="Add Resident">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <x-card title="New Resident">
                <form action="{{ route('residents.store') }}" method="POST">
                    @csrf

 <div class="mb-3">
    <label for="user_name" class="form-label">User Account Name</label>
    <input 
        type="text" 
        name="user_name" 
        id="user_name" 
        class="form-control @error('user_name') is-invalid @enderror" 
        placeholder="Type resident account name" 
        value="{{ old('user_name') }}"
        required
    >
    @error('user_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input 
        type="email" 
        name="email" 
        id="email" 
        value="{{ old('email') }}" 
        class="form-control @error('email') is-invalid @enderror" 
        placeholder="Type resident email"
        required
    >
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input 
        type="password" 
        name="password" 
        id="password" 
        class="form-control @error('password') is-invalid @enderror" 
        placeholder="Type resident password"
        required
    >
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <small class="text-muted">Minimum 8 characters.</small>
</div>

                    <div class="mb-3">
                        <label class="form-label">Household</label>
                        <select name="household_id" class="form-select @error('household_id') is-invalid @enderror">
                            <option value="">No household assigned</option>
                            @foreach($households as $household)
                                <option value="{{ $household->id }}" {{ old('household_id') == $household->id ? 'selected' : '' }}>
                                    {{ $household->address }} - {{ $household->barangay }}
                                </option>
                            @endforeach
                        </select>
                        @error('household_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-control @error('middle_name') is-invalid @enderror">
                            @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="form-label">Birth Date</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="form-control @error('birth_date') is-invalid @enderror" required>
                            @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="">Choose gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="form-control @error('contact_number') is-invalid @enderror" required>
                            @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('residents.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Resident</button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-layout>
